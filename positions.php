<?php
require_once 'core/init.php';

$user = new User();
if (!$user->isLoggedIn()) {
    Redirect::to('index.php');
}
if (!$user->hasPermission('admin')) {
    Redirect::to('index.php');
}

if (Input::exists('new', 'post')) {

    if (Token::check(Input::get('token'))) {
        
        $validate = new Validate();
        $validation = $validate->check($_POST, array(
            'name' => array(
                'required' => TRUE,
                'min' => 3,
                'unique' => 'positions',
                'display' => 'Cargo'
            ),
            'description' => array(
                'required' => true,
                'display' => 'Descripción'
            )
        ));

        if ($validation->passed()) {
            $position = new Position();
            
            try{
                
                $position->create(array(
                    'name' => escape(Input::get('name')),
                    'description' => escape(Input::get('description'))
                ));
                
                Session::flash('position', array('Haz resgistrado un nuevo cargo'));
                Redirect::to('positions.php');
                
            } catch (Exception $e) {
                die($e->getMessage() );
            }
            
        } else {
            $response = $validation->errors();
        }
    }
}

if (Input::exists('edit', 'post')) {

    if (Token::check(Input::get('token'))) {
        
        $validate = new Validate();
        $validation = $validate->check($_POST, array(
            'name' => array(
                'required' => TRUE,
                'min' => 3,
                'unique' => 'positions',
                'display' => 'Cargo'
            ),
            'description' => array(
                'required' => true,
                'display' => 'Descripción'
            )
        ));

        if ($validation->passed()) {
            $position = new Position();
            
            try{
                
                $position->update(array(
                    'name' => escape(Input::get('name')),
                    'description' => escape(Input::get('description'))
                ), escape(Input::get('id')));
                
                Session::flash('position', array('Haz editado exitosamente el cargo'));
                Redirect::to('positions.php');
                
            } catch (Exception $e) {
                die($e->getMessage() );
            }
            
        } else {
            $response = $validation->errors();
        }
    }
}

if (Input::exists('delete', 'post')) {

    $position = new Position();

    try{

        $position->delete( array( 'id', '=', Input::get('id_del') ) );
        
        Session::flash('position', array('Haz borrado el cargo exitosamente'));
        Redirect::to('positions.php');
        
    } catch (Exception $e) {
        die($e->getMessage() );
    }

}
?>

<?php get_template('header') ?>
<?php get_template('side-menu'); ?>
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
        <h1 class="page-header">Registro de Cargos</h1>
        <?php if (Session::exists('position')) {
            handle_messages(Session::flash('position'));
        }
        if (!empty($response)) {
            handle_messages($response, 'danger');
        }?>
        <?php if (!Input::exists('edit', 'get')) { ?>
            <?php $position = new Position();
            $positions = $position->get(array('id', '>', 0)); ?>
            <table class="table table-striped">
                <thead style="width:100%">
                    <tr>
                        <th># id</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody style="width:100%">
                    <?php foreach($positions as $value) { ?>
                    <tr>
                        <td><?php echo $value->id;?></td>
                        <td><?php echo $value->name;?></td>
                        <td><?php echo $value->description;?></td>
                        <td><a class="btn btn-link" href="positions.php?edit=<?php echo $value->id;?>">Editar</a>
                            <form style="display:inline-block" action="#" method="POST">
                                <input type="hidden" name="id_del" value="<?php echo $value->id;?>" />
                                <input type="submit" name="delete" class="btn btn-link" value="Eliminar" />
                            </form>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            <h2>Registrar Nuevo</h2>
            <form class="form-center" action="#" method="POST">

                <label for="inputName">Cargo:</label>
                <input name="name" type="text" id="inputName" class="form-control" value="<?php echo escape(Input::get('name')); ?>">

                <label for="inputDescription">Descripción:</label>
                <input name="description" type="text" id="inputDescription" class="form-control" value="<?php echo escape(Input::get('description')); ?>">
                </br>

                <input type="hidden" name="token" value="<?php echo Token::generate();?>">
                <input class="btn btn-lg btn-primary btn-block" name="new" type="submit" value="Registrar">
            </form>
        <?php } else { ?>

            <?php $position = new Position();
            $current_position = $position->getById(Input::get('edit', 'GET'));?>

            <form class="form-center" action="#" method="POST">

                <label for="inputName">Cargo:</label>
                <input name="name" type="text" id="inputName" class="form-control" value="<?php echo escape($current_position->name); ?>">

                <label for="inputDescription">Descripción:</label>
                <input name="description" type="text" id="inputDescription" class="form-control" value="<?php echo escape($current_position->description); ?>">
                </br>

                <input type="hidden" name="id" value="<?php echo escape($current_position->id); ?>">
                <input type="hidden" name="token" value="<?php echo Token::generate();?>">
                <input class="btn btn-lg btn-primary btn-block" name="edit" type="submit" value="Editar">
            </form>
        <?php } ?>
    </div>
<?php get_template('footer'); ?>
