<?php
    if(!defined('PLX_ROOT')) {
        die('LOST!');
    }

    class plxDemo extends plxPlugin {
        const HOOKS = array(
			'AdminPrepend',
			'AdminAuth',
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
?>		$getOkay= array('p','a','sel');
		foreach ($_GET as $key => $value) if(!in_array($key , $getOkay)) $_GET[$key] =''; // tri sur $_GET

		if(isset($_SESSION['user']) && $plxAdmin->aUsers[$_SESSION['user']]['login'] =='demo' && basename($_SERVER['SCRIPT_FILENAME']) !== 'auth.php' ){
			plxMsg::Error($plxAdmin->plxPlugins->aPlugins['plxDemo']->getLang('L_DEMO_ONLY'));
			foreach($plxAdmin->aUsers as $_userid => $_user) if ( $plxAdmin->aUsers[$_userid]['login'] !=='demo') $plxAdmin->aUsers[$_userid]['delete']=1;;
			}
		if(!empty($_POST) && $plxAdmin->aUsers[$_SESSION['user']]['login'] =='demo' && basename($_SERVER['SCRIPT_FILENAME']) !== 'auth.php'){// declinaison possible pour plusieurs profils
			if (!isset($_POST['preview'])) {
				$_POST= array(); // just in case ...
				header('Location: '.$_SERVER['HTTP_REFERER']);
				exit;
				}
			}
<?php
            echo self::END_CODE;						
        }
		
 public function AdminAuth() {
			
            echo self::BEGIN_CODE;
?>		
		echo '<p style="text-align:left">
				Login de connexion: <strong>demo</strong><br />
				Mot de passe: <strong>demo</strong><br />
			</p>';
<?php
            echo self::END_CODE;						
        }
    }
