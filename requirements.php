<?php
require_once 'core/init.php';

$user = new User();
if (!$user->isLoggedIn()) {
    Session::flash('home', 'You are not logged in');
    Redirect::to('index.php');
}

if (Input::exists()) {
        
    $validate = new Validate();
    $validation = $validate->check($_POST, array(
        'name' => array(
            'required' => TRUE,
            'min' => 3,
            'max' => 50,
            'unique' => 'req',
            'display' => 'Nombre'
        ),
        'description' => array(
            'required' => TRUE,
            'min' => 4,
            'max' => 100,
            'unique' => 'req',
            'display' => 'Descripción',
        ),
        'time' => array(
            'required' => TRUE,
            'min' => 1,
            'max' => 2,
            'display' => 'Tiempo',
        )
    ));

    if ($validation->passed()) {
        $req = new Requirement();
        try{
            
            $req->create_req(array(
                'name' => Input::get('name'),
                'description' => Input::get('description'),
                'time' => Input::get('time')
            ));
            
            Session::flash('home', 'You have registered a requirement!');
            Redirect::to('index.php');
            
        } catch (Exception $e) {
            die($e->getMessage() );
        }
        
    } else {
        foreach ($validation->errors() as $error) {
            echo $error, '<br>';
        }
    }
}

get_template('header');
?>
<div class="col-sm-12">
    <h2><a href="./">Inicio</a></h2>
</div>
<form class="col-sm-12" action="" method="post">
    <div class="field">
        <label for="name">Nombre</label>
        <br>
        <input type="text" name="name" id="name" value="<?php echo escape(Input::get('name')); ?>">
        <br>
        <label for="time">Tiempo en Días</label>
        <br>
        <input type="text" name="time" id="time" value="<?php echo escape(Input::get('time')); ?>">
        <br>
        <label for="description">Descripción del Requisíto</label>      
        <br>
        <textarea name="description" id="description" cols="30" rows="10"></textarea>
        <br>
    </div>
    <input type="submit" value="Register">
</form>
<?php 

get_template('footer');
