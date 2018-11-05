<?php
/**
 * This file is part of workerman.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the MIT-LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @author walkor<walkor@workerman.net>
 * @copyright walkor<walkor@workerman.net>
 * @link http://www.workerman.net/
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */

/**
 * 用于检测业务代码死循环或者长时间阻塞等问题
 * 如果发现业务卡死，可以将下面declare打开（去掉//注释），并执行php start.php reload
 * 然后观察一段时间workerman.log看是否有process_timeout异常
 */
//declare(ticks=1);

/**
 * 聊天主逻辑
 * 主要是处理 onMessage onClose
 */
use \GatewayWorker\Lib\Gateway;
use \Workerman\Connection\AsyncTcpConnection;

class Events {

	// public static function onWorkerStart($task) {

	// }

	// 当有客户端连接时，将client_id返回，让mvc框架判断当前uid并执行绑定
	public static function onConnect($client_id) {
		Gateway::sendToClient($client_id, json_encode(array(
			'type'      => 'init',
			'client_id' => $client_id,
		)));
	}

	/**
	 * 有消息时
	 * @param int $client_id
	 * @param mixed $message
	 */
	public static function onMessage($client_id, $message) {

		// 客户端传递的是json数据
		$request = json_decode($message, true);
		if (!$request) {
			return;
		}

		if ($request['type'] != 'pong') {
			// debug
			echo "client:{$_SERVER['REMOTE_ADDR']}:{$_SERVER['REMOTE_PORT']} gateway:{$_SERVER['GATEWAY_ADDR']}:{$_SERVER['GATEWAY_PORT']}  client_id:$client_id session:" . json_encode($_SESSION) . " onMessage:" . $message . "\n";
		}

		// 根据类型执行不同的业务
		switch ($request['type']) {
			// 客户端回应服务端的心跳
			case 'pong':
				return;
			case 'init':

				$_SESSION['group_id'] = $request['group_id'];
				$_SESSION['bid']      = $request['bid'];

				return;
		}
	}

	/**
	 * 当客户端断开连接时
	 * @param integer $client_id 客户端id
	 */
	public static function onClose($client_id) {
		// debug
		echo "client:{$_SERVER['REMOTE_ADDR']}:{$_SERVER['REMOTE_PORT']} gateway:{$_SERVER['GATEWAY_ADDR']}:{$_SERVER['GATEWAY_PORT']}  client_id:$client_id onClose:''\n";

		if (isset($_SESSION['group_id'])) {

			$tcp_connection = new AsyncTcpConnection('tcp://pin.lnest.cc:80');
			// 当连接建立成功时，发送http请求数据
			$tcp_connection->onConnect = function ($tcp_connection) {
				echo "connect success\n";

				$url = '/chat/onClose?group_id=' . $_SESSION['group_id'] . (isset($_SESSION['bid']) ? '&bid=' . $_SESSION['bid'] : '');

				echo $url . "\n";

				$tcp_connection->send("GET " . $url . " HTTP/1.1\r\nHost: pin.lnest.cc\r\nConnection: keep-alive\r\n\r\n");
			};
			$tcp_connection->onMessage = function ($tcp_connection, $http_buffer) {
				echo $http_buffer;
			};
			$tcp_connection->onClose = function ($tcp_connection) {
				echo "connection closed\n";
			};
			$tcp_connection->onError = function ($tcp_connection, $code, $msg) {
				echo "Error code:$code msg:$msg\n";
			};
			$tcp_connection->connect();
		}

	}

}
