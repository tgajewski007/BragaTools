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
				CookieManager::set(CookieManager::MESSAGE_ALERT, $msg);
				break;
			case Message::MESSAGE_INFO:
				CookieManager::set(CookieManager::MESSAGE_INFO, $msg);
				break;
			case Message::MESSAGE_SQL:
				CookieManager::set(CookieManager::MESSAGE_SQL, $msg);
				break;
			case Message::MESSAGE_WARNING:
				CookieManager::set(CookieManager::MESSAGE_WARNING, $msg);
				break;
		}
	}
	// -----------------------------------------------------------------------------------------------------------------
	public function restore(Message $message)
	{
		if(CookieManager::isExist(CookieManager::MESSAGE_ALERT))
		{
			$msg = CookieManager::get(CookieManager::MESSAGE_ALERT);
			foreach($msg as $m)
			/** @var Message $m */
			{
				$message->save(Message::MESSAGE_ALERT, $m);
			}
			CookieManager::kill(CookieManager::MESSAGE_ALERT);
		}

		if(CookieManager::isExist(CookieManager::MESSAGE_INFO))
		{
			$msg = CookieManager::get(CookieManager::MESSAGE_INFO);
			foreach($msg as $m)
			/** @var Message $m */
			{
				$message->save(Message::MESSAGE_INFO, $m);
			}
			CookieManager::kill(CookieManager::MESSAGE_INFO);
		}

		if(CookieManager::isExist(CookieManager::MESSAGE_SQL))
		{
			$msg = CookieManager::get(CookieManager::MESSAGE_SQL);
			foreach($msg as $m)
			/** @var Message $m */
			{
				$message->save(Message::MESSAGE_SQL, $m);
			}
			CookieManager::kill(CookieManager::MESSAGE_SQL);
		}

		if(CookieManager::isExist(CookieManager::MESSAGE_WARNING))
		{
			$msg = CookieManager::get(CookieManager::MESSAGE_WARNING);
			foreach($msg as $m)
			/** @var Message $m */
			{
				$message->save(Message::MESSAGE_WARNING, $m);
			}
			CookieManager::kill(CookieManager::MESSAGE_WARNING);
		}
	}
	// -----------------------------------------------------------------------------------------------------------------
}