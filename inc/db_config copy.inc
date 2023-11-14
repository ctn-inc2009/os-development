<?php
$config = array(//データベースタイプ
				'db_type' => 'mysql',
				//データベースホスト
				'db_host' => 'localhost',
				//データベース名
				'db_name' => 'tb_os-inc',
				//ユーザー名
				'db_user' => 'osinc',
				//パスワード
				'db_pass' => 'Yqso80_3',
				);

$dsn = sprintf("%s://%s:%s@%s/%s",
				$config['db_type'],
				$config['db_user'],
				$config['db_pass'],
				$config['db_host'],
				$config['db_name']
				);
?>
