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
		foreach ($_GET as $key => $value) if($key !='p') $_GET[$key] =''; // tri sur $_GET
		if(isset($_SESSION['user']) && $plxAdmin->aUsers[$_SESSION['user']]['login'] =='demo' && basename($_SERVER['SCRIPT_FILENAME']) !== 'auth.php' ){plxMsg::Error($plxAdmin->plxPlugins->aPlugins['plxDemo']->getLang('L_DEMO_ONLY'));}
		if(!empty($_POST) && $plxAdmin->aUsers[$_SESSION['user']]['login'] =='demo' && basename($_SERVER['SCRIPT_FILENAME']) != 'auth.php'){// declinaison possible pour plusieurs profils
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
