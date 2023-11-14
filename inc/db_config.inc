<?php
$config = array(//�f�[�^�x�[�X�^�C�v
				'db_type' => 'mysql',
				//�f�[�^�x�[�X�z�X�g
				'db_host' => 'localhost',
				//�f�[�^�x�[�X��
				'db_name' => 'xs557112_osinc',
				//���[�U�[��
				'db_user' => 'xs557112_osinc',
				//�p�X���[�h
				'db_pass' => '	ctn200911',
				);

$dsn = sprintf("%s://%s:%s@%s/%s",
				$config['db_type'],
				$config['db_user'],
				$config['db_pass'],
				$config['db_host'],
				$config['db_name']
				);
?>
