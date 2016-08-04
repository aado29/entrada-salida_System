<?php
require_once 'core/init.php';
$user = new User();

if(!$user->isLoggedIn()){
    Redirect::to('index.php');
}
if (Input::exists()) {
    if(Token::check(Input::get('token'))){
        $validate = new Validate();
        $validation = $validate->check($_POST, array(
           'password_current' => array(
               'required' => true,
               'display' => 'Contraseña Actual'
           ),
            'password_new' => array(
                'required' => true,
                'min' => 6,
                'display' => 'Nueva Contraseña'
            ),
            'password_new_again' => array(
                'required' => true,
                'min' => 6,
                'matches' => 'password_new',
                'display' => 'Nueva Contraseña 2'
            )
        ));
        if($validation->passed()){
            if(Hash::make(Input::get('password_current'), $user->data()->salt) !== $user->data()->password){
                $response = array('Tu contraseña actual es invalida');
            }else{
                $salt = Hash::salt(32);
                $user->update(array(
                    'password' => Hash::make(Input::get('password_new'), $salt),
                    'salt' => $salt
                ));
                Session::flash('change', array('Haz cambiado la contraseña exitosamente!!'));
                Redirect::to('changepassword.php');
            }
        }else{
            $response = $validation->errors();
        }
    }
}
?>
<?php get_template('header') ?>
<?php get_template('side-menu'); ?>
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
        <h1 class="page-header">Cambio de Contraseña</h1>
        <?php if (Session::exists('change')) {
            handle_messages(Session::flash('change'));
        }
        if (!empty($response)) {
            handle_messages($response, 'danger');
        }?>
        <form class="form-center" action="#" method="POST">

            <label for="passwordCurrent">Contraseña Actual</label>
            <input name="password_current" type="password" id="passwordCurrent" class="form-control" >

            <label for="passwordNew">Nueva Contraseña</label>
            <input name="password_new" type="password" id="passwordNew" class="form-control" >

            <label for="passwordNewAgain">Repita la nueva Contraseña</label>
            <input name="password_new_again" type="password" id="passwordNewAgain" class="form-control" >

            <button class="btn btn-md btn-primary btn-block" type="submit">Cambiar</button>
            <input type="hidden" name="token" value="<?php echo Token::generate();?>">
        </form> 
    </div>
<?php get_template('footer') ?>
