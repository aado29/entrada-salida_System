<?php
require_once 'core/init.php';

$user = new User();
if (!$user->isLoggedIn()) {
    Redirect::to('index.php');
}
if (!$user->hasPermission('admin')) {
    Redirect::to('index.php');
}

if (Input::exists()) {

    if (Token::check(Input::get('token'))) {
        
        $validate = new Validate();
        $validation = $validate->check($_POST, array(
            'firstName' => array(
                'name' => true,
                'required' => true,
                'min' => 2,
                'max' => 20,
                'display' => 'Nombre'
            ),
            'lastName' => array(
                'name' => true,
                'required' => true,
                'min' => 2,
                'max' => 20,
                'display' => 'Apellido'
            ),
            'id_type' => array(
                'required' => true,
                'display' => 'Tipo de Indentificacion'
            ),
            'id_num' => array(
                'required' => true,
                'unique' => 'users',
                'numeric' => true,
                'display' => 'Numero de Identificacion'
            ),
            'password' => array(
                'required' => TRUE,
                'min' => 6,
                'display' => 'Contraseña'
            ),
            'password_again' => array(
                'required' => TRUE,
                'matches' => 'password',
                'display' => 'Contraseña 2'
            ),
            'email' => array(
                'email' => true,
                'required' => true,
                'email' => true,
                'display' => 'Email'
            ),
            'group' => array(
                'required' => TRUE,
                'display' => 'Tipo de Usuario'
            ),
            'position' => array(
                'required' => TRUE,
                'display' => 'Cargo'
            )
        ));

        if ($validation->passed()) {
            $user = new User();
            
            $salt = Hash::salt(32);
            try{
                
                $user->create(array(
                    'id_type' => escape(Input::get('id_type')),
                    'id_num' => escape(Input::get('id_num')),
                    'email' => escape(Input::get('email')),
                    'password' => Hash::make(Input::get('password'), $salt),
                    'salt' => $salt,
                    'firstName' => escape(Input::get('firstName')),
                    'lastName' => escape(Input::get('lastName')),
                    'joined' => date('Y-m-d H:i:s'),
                    'id_group' => escape(Input::get('group')),
                    'id_position' => escape(Input::get('position'))
                ));
                
                Session::flash('register', array('Haz resgistrado un nuevo usuario'));
                Redirect::to('register.php');
                
            } catch (Exception $e) {
                die($e->getMessage() );
            }
            
        } else {
            $response = $validation->errors();
        }
    }
}
?>

<?php get_template('header') ?>
<?php get_template('side-menu'); ?>
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
        <h1 class="page-header">Registro</h1>
        <?php if (Session::exists('register')) {
            handle_messages(Session::flash('register'));
        }
        if (!empty($response)) {
            handle_messages($response, 'danger');
        }?>
        <form class="form-center" action="#" method="POST">

            <label for="inputFirstName">Nombre:</label>
            <input name="firstName" type="text" id="inputFirstName" class="form-control" value="<?php echo escape(Input::get('firstName')); ?>">

            <label for="inputLastName">Apellido:</label>
            <input name="lastName" type="text" id="inputLastName" class="form-control" value="<?php echo escape(Input::get('lastName')); ?>">

            <label for="inputId">Identificación</label>
            <div class="form-group form-inline">
                <select name="id_type" id="inputId" class="form-control input-group">
                    <option value="V">V</option>
                    <option value="E">E</option>
                </select>
                <input name="id_num" type="text" id="inputIdNum" class="form-control input-group" value="<?php echo escape(Input::get('id_num')); ?>">
            </div>
            
            <label for="inputPass">Contraseña:</label>
            <input name="password" type="password" id="inputPass" class="form-control">

            <label for="inputPass_">Repita Contraseña:</label>
            <input name="password_again" type="password" id="inputPass_" class="form-control">
            
            <label for="inputEmail">Email:</label>
            <input name="email" type="text" id="inputEmail" class="form-control" value="<?php echo escape(Input::get('email')); ?>" placeholder="email@dominio.ext">

            <label for="inputPosition">Cargo:</label>
            <select name="position" id="inputPosition" class="form-control">
                <option selected="selected">------------------</option>
                <?php foreach (getPositions() as $key => $value) { ?>
                    <option value="<?php echo $value->id ?>"><?php echo $value->name; ?></option> 
                <?php } ?>
            </select>

            <label for="inputGroup">Tipo de Usuario:</label>
            <select name="group" id="inputGroup" class="form-control">
                <option selected="selected">------------------</option>
                <?php foreach (getGroups() as $key => $value) { ?>
                    <option value="<?php echo $value->id ?>"><?php echo $value->name; ?></option> 
                <?php } ?>
            </select>
            </br>


            <input type="hidden" name="token" value="<?php echo Token::generate();?>">
            <button class="btn btn-md btn-primary btn-block" type="submit">Registrar</button>
        </form>
    </div>
<?php get_template('footer'); ?>
