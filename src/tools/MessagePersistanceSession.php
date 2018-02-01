<?php
namespace braga\tools\tools;
/**
 * Created on 26 sty 2018 10:05:53
 * error prefix
 * @author Tomasz Gajewski
 * @package
 *
 */
class MessagePersistanceSession implements IMessagePersistance
{
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 *
	 * {@inheritdoc}
	 * @see \braga\tools\tools\IMessagePersistance::store()
	 */
	public function store($typ, $msg)
	{
		switch($typ)
		{
			case Message::MESSAGE_ALERT:
				SessManager::set(SessManager::MESSAGE_ALERT, $msg);
				break;
			case Message::MESSAGE_INFO:
				SessManager::set(SessManager::MESSAGE_INFO, $msg);
				break;
			case Message::MESSAGE_SQL:
				SessManager::set(SessManager::MESSAGE_SQL, $msg);
				break;
			case Message::MESSAGE_WARNING:
				SessManager::set(SessManager::MESSAGE_WARNING, $msg);
				break;
		}
	}
	// -----------------------------------------------------------------------------------------------------------------
	public function restore(Message $message)
	{
		if(SessManager::isExist(SessManager::MESSAGE_ALERT))
		{
			$msg = SessManager::get(SessManager::MESSAGE_ALERT);
			foreach($msg as $m)
			/** @var Message $m */
			{
				$message->save(Message::MESSAGE_ALERT, $m);
			}
			SessManager::kill(Message::MESSAGE_ALERT);
		}

		if(SessManager::isExist(SessManager::MESSAGE_INFO))
		{
			$msg = SessManager::get(SessManager::MESSAGE_INFO);
			foreach($msg as $m)
			/** @var Message $m */
			{
				$message->save(Message::MESSAGE_INFO, $m);
			}
			SessManager::kill(SessManager::MESSAGE_INFO);
		}

		if(SessManager::isExist(SessManager::MESSAGE_SQL))
		{
			$msg = SessManager::get(SessManager::MESSAGE_SQL);
			foreach($msg as $m)
			/** @var Message $m */
			{
				$message->save(Message::MESSAGE_SQL, $m);
			}
			SessManager::kill(SessManager::MESSAGE_SQL);
		}

		if(SessManager::isExist(SessManager::MESSAGE_WARNING))
		{
			$msg = SessManager::get(SessManager::MESSAGE_WARNING);
			foreach($msg as $m)
			/** @var Message $m */
			{
				$message->save(Message::MESSAGE_WARNING, $m);
			}
			SessManager::kill(SessManager::MESSAGE_WARNING);
		}
	}
	// -----------------------------------------------------------------------------------------------------------------
}