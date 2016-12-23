<?php

class BLC {
	const API_CODE = '1770d5d9-bcea-4d28-ad21-6cbd5be018a8'; // 01aec668-8739-462c-93c2-25a13318457b
	const WALLET_ID = 'ZjI3OGY2YmQtYzAwZC00NWZhLTg3OWUtNjk2ZWM4ZWYwOGRi';
	const WALLET_PWD = 'Z2lhbmdudDc3MDA3Nw==';// Z2lhbmdudDc3MDA3Nw== //Z1A5OXpYWFM3NzAwNzc=
	const WALLET_PWD2 = '123456';
	const SERVICE_URL = 'http://45.32.255.50:3000/';// http://45.32.255.50:3000
	const WALLET_PIN_ADDRESS = '1P31kHNco4F7EvvXzALEKaK9N3P7iofsVi';
	const FEE = '0.00001'; // 0.00001 // 0.00019361
	const STOCK_ID = '1CgLUw9fEStxLnJdUGwa2FSBNexfiYvV1r';

	public static function getPWD(){
		return base64_decode(self::WALLET_PWD);
	}

	public  static function getID() {
		return base64_decode(self::WALLET_ID);
	}
}

class SYS {
	const PIN_COST = 0.011;
	const SYS_PIN_COST = 'sys_setting';
}

class CFG {
	public  $request_pin_cost;
}