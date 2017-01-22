<?php
namespace braga\tools\tools;
/**
 * Klasa utworzona na potrzeby zorganizowania kodu do przechwytywania i logownaia błędów.
 * Nie należy jej używać w kodzie oprócz metody ::setErrorHandler
 * we wczesnej fazie wykonywania skryptu (np. base.php, config.php, ...).
 * Data utworzenia 7 lip 2016
 * @author KlewinowskiKarol
 * @package core
 */
class ErrorHandler
{
	// -------------------------------------------------------------------------
	protected static $logFolder;
	// -------------------------------------------------------------------------
	private function __construct()
	{
	}
	// -------------------------------------------------------------------------
	/**
	 * Metoda do ustawienia przechwytywania i logowania błędów.
	 * Nie nalezy jej używać nigdzie oprócz base.php
	 */
	public static function setErrorHandler($logFolder)
	{
		self::$logFolder = $logFolder;
		register_shutdown_function(array(
				__NAMESPACE__ . '\ErrorHandler',
				'fatalErrorHandler'));
		set_error_handler(array(
				__NAMESPACE__ . '\ErrorHandler',
				'errorHandler'));
		set_exception_handler(array(
				__NAMESPACE__ . '\ErrorHandler',
				'exceptionHandler'));
	}
	// -------------------------------------------------------------------------
	protected static function getCallStack()
	{
		ob_start();
		debug_print_backtrace();
		$trace = ob_get_contents();
		ob_end_clean();
		$trace = preg_replace('/^#0\s+' . __FUNCTION__ . "[^\n]*\n/", '', $trace, 1);
		$trace = preg_replace_callback('/^#(\d+)/m', function ($m)
		{
			return ('#' . (intval($m[1]) - 1));
		}, $trace);

		return $trace;
	}
	// -------------------------------------------------------------------------
	/**
	 * Nie używać metody poza jej klasą!!!
	 */
	public static function errorHandler($errno, $errstr, $errfile, $errline)
	{
		$retval = date("Y-m-d H:i:s");
		$retval .= ";" . $errno;
		$retval .= ";" . $errstr;
		$retval .= ";" . $errfile;
		$retval .= ";" . $errline;
		$retval .= "\n" . self::getCallStack();
		$retval .= "\n";
		switch($errno)
		{
			case E_ERROR:
				$filePrefix = "error";
				break;
			case E_WARNING:
				$filePrefix = "warn";
				break;
			case E_PARSE:
				$filePrefix = "parse";
				break;
			case E_NOTICE:
				$filePrefix = "notice";
				break;
			case E_CORE_ERROR:
				$filePrefix = "core_error";
				break;
			case E_CORE_WARNING:
				$filePrefix = "core_warn";
				break;
			case E_COMPILE_ERROR:
				$filePrefix = "compile_error";
				break;
			case E_COMPILE_WARNING:
				$filePrefix = "compile_warn";
				break;
			case E_USER_ERROR:
				$filePrefix = "user_error";
				break;
			case E_USER_WARNING:
				$filePrefix = "user_warn";
				break;
			case E_USER_NOTICE:
				$filePrefix = "user_notice";
				break;
			case E_STRICT:
				$filePrefix = "strict";
				break;
			case E_RECOVERABLE_ERROR:
				$filePrefix = "recoverable_error";
				self::saveErrorToLogFile($filePrefix, $retval);
				throw new \Exception($errstr, $errno);
				break;
			case E_DEPRECATED:
				$filePrefix = "deprec";
				break;
			case E_USER_DEPRECATED:
				$filePrefix = "deprec_error";
				break;
			case E_ALL:
				$filePrefix = "all_error";
				break;
			default :
				$filePrefix = "unknow";
				break;
		}
		self::saveErrorToLogFile($filePrefix, $retval);
		return false;
	}
	// -----------------------------------------------------------------------------
	/**
	 * Nie używać metody poza jej klasą!!!
	 */
	public static function exceptionHandler(\Exception $exception)
	{
		$filePrefix = "exception";
		$retval = date("Y-m-d H:i:s");
		$retval .= ";" . $exception->getCode();
		$retval .= ";" . $exception->getMessage();
		$retval .= ";" . $exception->getFile();
		$retval .= ";" . $exception->getLine();
		$retval .= "\n";
		self::saveErrorToLogFile($filePrefix, $retval);
		return false;
	}
	// -----------------------------------------------------------------------------
	/**
	 * Nie używać metody poza jej klasą!!!
	 */
	public static function fatalErrorHandler()
	{
		$error = error_get_last();
		if($error !== null)
		{
			switch($error["type"])
			{
				case E_ERROR:
				case E_PARSE:
				case E_CORE_ERROR:
				case E_COMPILE_ERROR:
				case E_USER_ERROR:
					self::errorHandler($error["type"], $error["message"], $error["file"], $error["line"]);
					exit();
					break;
			}
		}
	}
	// -----------------------------------------------------------------------------
	/**
	 * Funkcja do zapisu plików *.log w katalogu LOG_DIRECTORY
	 * @param string $filePrefix
	 * @param string $retval
	 */
	private static function saveErrorToLogFile($filePrefix, $retval)
	{
		$file = self::$logFolder . $filePrefix . "." . date("Y-m-d") . ".log";
		$h = @fopen($file, "a");
		@fwrite($h, $retval, strlen($retval));
		@fclose($h);
	}
	// -----------------------------------------------------------------------------
}