<?php
    if(!defined('PLX_ROOT')) {
        die('LOST!');
    }

    class plxDemo extends plxPlugin {
        const HOOKS = array(
			'AdminPrepend',
        );
        const BEGIN_CODE = '<?php' . PHP_EOL;
        const END_CODE = PHP_EOL . '?>';
		
        public function __construct($default_lang) {
            # appel du constructeur de la classe plxPlugin (obligatoire)
            parent::__construct($default_lang);

            # Ajoute des hooks
            foreach(self::HOOKS as $hook) {
                $this->addHook($hook, $hook);
            }
        }

 public function AdminPrepend() {
			
            echo self::BEGIN_CODE;
?>		
		if($plxAdmin->aUsers[$_SESSION['user']]['login'] =='demo'){plxMsg::Error($plxAdmin->plxPlugins->aPlugins['plxDemo']->getLang('L_DEMO_ONLY'));}
		if(!empty($_POST) && $plxAdmin->aUsers[$_SESSION['user']]['login'] =='demo'){// declinaison possible pour plusieurs profils
			if (!isset($_POST['preview'])) {
				$_POST= array(); // just in case ...
				header('Location: '.$_SERVER['HTTP_REFERER']);
				exit;
				}
			}
<?php
            echo self::END_CODE;						
        }
    }