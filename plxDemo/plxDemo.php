<?php
    if(!defined('PLX_ROOT')) {
        die('LOST!');
    }

    class plxDemo extends plxPlugin {
        const HOOKS = array(
			'AdminPrepend',
			'AdminAuth',
			'plxMotorGetUsers',
			'AdminCommentsTop',
			'AdminUsersTop',
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
# filtre les variables $_GET et $_POST pour les utilisateurs de démonstration.
 public function AdminPrepend() {
			
            echo self::BEGIN_CODE;
?>
		if(isset( $_SESSION['user']) && $_SESSION['user'] =='001') { goto skipDemoProfils;}
		$demoUser = array( L_PROFIL_ADMIN, L_PROFIL_MANAGER , L_PROFIL_MODERATOR , L_PROFIL_EDITOR, L_PROFIL_WRITER );		
		$getOkay= array('p','a','sel','d','page','help','c');
		foreach ($_GET as $key => $value) if(!in_array($key , $getOkay)) $_GET[$key] =''; // tri sur $_GET

		if(isset($_SESSION['user']) && in_array( $plxAdmin->aUsers[$_SESSION['user']]['login'], $demoUser)   && basename($_SERVER['SCRIPT_FILENAME']) !== 'auth.php' ){
			plxMsg::Error($plxAdmin->plxPlugins->aPlugins['plxDemo']->getLang('L_DEMO_ONLY'));
			   foreach($plxAdmin->aUsers as $_userid => $_user) {
				   if (!in_array($plxAdmin->aUsers[$_userid]['login'], $demoUser)) {
					   $plxAdmin->aUsers[$_userid]['delete']=1 ;
				  }
			   }
			}
		if(!empty($_POST) &&  in_array( $plxAdmin->aUsers[$_SESSION['user']]['login'], $demoUser) && basename($_SERVER['SCRIPT_FILENAME']) !== 'auth.php'){
			if (!isset($_POST['preview'])) {
				$_POST= array(); // just in case ...
				header('Location: '.$_SERVER['HTTP_REFERER']);
				exit;
				}
			}
		$plxAdmin->aUsers = array_map("unserialize", array_unique(array_map("serialize", $plxAdmin->aUsers)));
		skipDemoProfils:
<?php
            echo self::END_CODE;						
        }
# donne dans le formulaire les infos de login et passe pour le mode démonstration		
 public function AdminAuth() {
			
            echo self::BEGIN_CODE;
?>		
		echo '<dl style="display:grid;grid-template-columns:minmax(8em,1fr) 1fr">
				<dt>'.$plxAdmin->plxPlugins->aPlugins['<?=__CLASS__?>']->getLang('L_DEMO_PROFILS').':</dt>
				<dd><strong>'. L_PROFIL_ADMIN.'</strong><br /><strong>'. L_PROFIL_MANAGER.'</strong><br /><strong>'. L_PROFIL_MODERATOR.'</strong><br /><strong>'. L_PROFIL_EDITOR.'</strong><br /><strong>'. L_PROFIL_WRITER.'</strong></dd>
				<hr style="grid-column:1/-1">
				<dt>'.$plxAdmin->plxPlugins->aPlugins['<?=__CLASS__?>']->getLang('L_DEMO_SINGLE_PASS').':</dt>
				<dd><strong>demo</strong></dd>
			</dl>';

<?php
            echo self::END_CODE;						
        }
		
#injecte les utilisateurs de demonstration au tableau des aUsers		
 public function plxMotorGetUsers() {
			
            echo self::BEGIN_CODE;
?>		
#on fait sauter le plugin pour l'administrateur
if(isset( $_SESSION['user']) && $_SESSION['user'] =='001') { goto skipDemoProfils;}


# Chargement des fichiers de langue en fonction du profil de l'utilisateur connecté
loadLang(PLX_CORE.'lang/'.$lang.'/admin.php');
loadLang(PLX_CORE.'lang/'.$lang.'/core.php');

# on stocke la langue utilisée pour l'affichage de la zone d'administration en variable de session
# nb: la langue peut etre modifiée par le hook AdminPrepend via des plugins
$_SESSION['admin_lang'] = $lang;

# on créer notre tableau d'utilisateurs de demonstration	
		$newDemoUsers = array(
		'001'=> array(
			'active' => '1' , 
			'delete'=>'0', 
			'profil'=>'0',
			'login'=>   L_PROFIL_ADMIN    ,
			'name'=> L_PROFIL_ADMIN ,
			'password'=>'7f95d084a71ee8408d4945e670104ddab5869a30',
			'salt'=>'xf8lQZmfDb',
			'infos'=>'',
			'email'=>'0@no.com',
			'lang'=>'fr',
			'password_token'=>'',
			'password_token_expiry'=>''
			),
		'002' => array(
			'active' => '1' , 
			'delete'=> '0', 
			'profil'=> '1',
			'login'=> L_PROFIL_MANAGER ,
			'name'=> L_PROFIL_MANAGER ,
			'password'=>'7f95d084a71ee8408d4945e670104ddab5869a30',
			'salt'=>'xf8lQZmfDb',
			'infos'=>'',
			'email'=>'1@no.com',
			'lang'=>'fr',
			'password_token'=>'',
			'password_token_expiry'=>''
			)	,
		'003' => array(
			'active' => '1' , 
			'delete'=>'0', 
			'profil'=>'2',
			'login'=> L_PROFIL_MODERATOR ,
			'name'=> L_PROFIL_MODERATOR ,
			'password'=>'7f95d084a71ee8408d4945e670104ddab5869a30',
			'salt'=>'xf8lQZmfDb',
			'infos'=>'',
			'email'=>'2@no.com',
			'lang'=>'fr',
			'password_token'=>'',
			'password_token_expiry'=>''
			),
		'004' => array(
			'active' => '1' , 
			'delete'=>'0', 
			'profil'=>'3',
			'login'=> L_PROFIL_EDITOR ,
			'name'=> L_PROFIL_EDITOR ,
			'password'=>'7f95d084a71ee8408d4945e670104ddab5869a30',
			'salt'=>'xf8lQZmfDb',
			'infos'=>'',
			'email'=>'3@no.com',
			'lang'=>'fr',
			'password_token'=>'',
			'password_token_expiry'=>''
			)	,
		'005' => array(
			'active' => '1' , 
			'delete'=>'0', 
			'profil'=>'4',
			'login'=> L_PROFIL_WRITER ,
			'name'=> L_PROFIL_WRITER ,
			'password'=>'7f95d084a71ee8408d4945e670104ddab5869a30',
			'salt'=>'xf8lQZmfDb',
			'infos'=>'',
			'email'=>'4@no.com',
			'lang'=>'fr',
			'password_token'=>'',
			'password_token_expiry'=>''
			)		
	);

        #on injecte nos utilisateurs de demonstration si en mode demo uniquement
		$nbUsers = count($this->aUsers);  
			foreach ($newDemoUsers as $key => $value){
               $nbUsers++;
               $this->aUsers[str_pad($nbUsers, 3, "0", STR_PAD_LEFT)] = $value; 
            }  
			
	skipDemoProfils:


<?php
            echo self::END_CODE;						
        }		
		
#cache les sites et email des commentaires aux utilisateurs de démonstration			
 public function AdminCommentsTop() {
			
            echo self::BEGIN_CODE;
?>		
		if(in_array( $plxAdmin->aUsers[$_SESSION['user']]['login'], $demoUser))  include(PLX_ROOT.'plugins/plxDemo/comments.php');		

<?php
            echo self::END_CODE;						
        } 
#cache les sites et email des commentaires aux utilisateurs de démonstration			
 public function AdminUsersTop() {
			
            echo self::BEGIN_CODE;
?>		
			//if(isset( $_SESSION['user']) && $_SESSION['user'] =='001') { unset($plxAdmin->plxPlugins->aPlugins['<?=__CLASS__?>']);}

<?php
            echo self::END_CODE;						
        } 

		
	}
?>
