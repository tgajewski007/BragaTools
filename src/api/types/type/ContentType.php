<?php

namespace braga\tools\api\types\type;

/**
 * Created 06.01.2023 11:41
 * error prefix
 * @autor Tomasz Gajewski
 */
enum ContentType: string
{
	case JSON = "application/json; charset=UTF-8";
	case PLAIN_TEXT = "text/plain; charset=UTF-8";
	case HTML = "text/html; charset=UTF-8";
	case PDF = "application/pdf";
	case EXCEL = "application/vnd.ms-excel";
	case XML = "text/xml; charset-utf-8";
	case DOWNLOAD = "application/x-download";
}
