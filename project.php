<?php
require_once 'core/init.php';

$project = new Project();
$requirement = new Requirement();
$user = new User();
if (!$user->isLoggedIn()) {
    Session::flash('home', 'You are not logged in');
    Redirect::to('index.php');
}
if (Input::get('id')) {
    if (Input::get('submit')) {
        try{
            
            $requirement->upload('req', Input::get('id'));
            
            Session::flash('project', 'You have registered the requirements!');
            Redirect::to('project.php');
            
        } catch (Exception $e) {
            die($e->getMessage() );
        }
    }
    if (Input::get('delete')) {
        try{
            
            $requirement->delete('req_del', Input::get('id'));

            Session::flash('project', 'You have deleted the requirements!');
            Redirect::to('project.php');
            
        } catch (Exception $e) {
            die($e->getMessage() );
        }
    }
    if (Input::get('approve')) {
        try{
            
            $requirement->approve('req_appr', Input::get('id'));

            Session::flash('project', 'You have approval the requirements!');
            Redirect::to('project.php');
            
        } catch (Exception $e) {
            die($e->getMessage() );
        }
    }
    if (Input::get('approved')) {
        if ($user->hasPermission('admin')) {
            try{
                $project->approveProject(Input::get('id'));

                Session::flash('project', 'Your Project has been approved!');
                Redirect::to('project.php');
                
            } catch (Exception $e) {
                die($e->getMessage() );
            }
        }
    }
} else {
    if (Input::exists()) {
            
        $validate = new Validate();
        $validation = $validate->check($_POST, array(
            'name' => array(
                'required' => TRUE,
                'min' => 3,
                'max' => 50,
                'uniques' => array(
                    'id' => $user->data()->id,
                    'field_id' => 'id_user',
                    'table' => 'projects'
                ),
                'display' => 'Nombre'
            ),
            'description' => array(
                'required' => TRUE,
                'min' => 2,
                'max' => 100,
                'uniques' => array(
                    'id' => $user->data()->id,
                    'field_id' => 'id_user',
                    'table' => 'projects'
                ),
                'display' => 'Descripción'
            )
        ));

        if ($validation->passed()) {
            try{
                
                $project->create(array(
                    'name' => Input::get('name'),
                    'description' => Input::get('description'),
                    'created' => date('Y-m-d H:i:s'),
                    'id_user' => $user->data()->id
                ));
                
                Session::flash('project', 'You have registered a project!');
                Redirect::to('project.php');
                
            } catch (Exception $e) {
                die($e->getMessage() );
            }
            
        } else {
            foreach ($validation->errors() as $error) {
                echo $error, '<br>';
            }
        }
    }
}

get_template('header');
if (Session::exists('project')) {
    echo '<p>'.Session::flash('project').'</p>';
}
if (Input::get('id')) { ?>

    <div class="row">
        <div class="col-sm-12">
            <h2><a href="./">Inicio</a></h2>
            <h2>Proyecto: <?php echo $project->get_project_part('name', Input::get('id')); ?></h2>
        </div>
        <form class="col-sm-4" action="" method="post">
            <h3>Agregar Requisitos</h3><hr>
            <div class="field">
                <?php if ($requirement->getRequirements()) {
                    foreach ($requirement->getRequirements() as $key => $value) {
                        if (!$requirement->match(Input::get('id'), $value->id)) {
                            echo '<p><input type="checkbox" id="requirements-'.$key.'" name="req[]" value="'.$value->id.'">';
                            echo '<label for="requirements-'.$key.'">'.$value->name.' - Tiempo: '.$value->time.' Días</label></br>';
                            if ($value->description)
                                echo $value->description.'</p><hr>';
                            else
                                echo 'Descripción no disponible</p><hr>';
                        }
                    }
                } ?>    
            </div>
            <input type="submit" name="submit" value="Register">
        </form>
        <form class="col-sm-4" action="" method="post">
            <h3>Borrar Requisitos</h3><hr>
            <div class="field">
                <?php $arr_req = $requirement->getRequirements(Input::get('id'));
                if ($arr_req) {
                    foreach ($arr_req as $key => $value) {
                        if ($value->approval == '1') {
                            echo '<input type="checkbox" id="requirements-'.$key.'" name="req_del[]" value="'.$requirement->getRequirementsPart($value->id_req, 'id').'">';
                            echo '<label for="requirements-'.$key.'">'.$requirement->getRequirementsPart($value->id_req, 'name').'</label></br><hr>';
                        }
                    }
                } ?>      
            </div>
            <input type="submit" name="delete" value="Delete">
        </form>
        <?php if($user->hasPermission('admin')) { ?>
            <form class="col-sm-4" action="" method="post">
                <h3>Aprobar Requisitos</h3><hr>
                <div class="field">
                    <?php $arr_req = $requirement->getRequirements(Input::get('id'));
                    if ($arr_req) {
                        foreach ($arr_req as $key => $value) {
                            if ($value->approval == '1') {
                                echo '<input type="checkbox" id="requirements-'.$key.'" name="req_appr[]" value="'.$value->id.'">';
                                echo '<label for="requirements-'.$key.'">'.$requirement->getRequirementsPart($value->id_req, 'name').'</label></br><hr>';
                            }else {
                                echo '<input type="checkbox" disabled="disabled" id="requirements-'.$key.'" name="req_appr[]" value="'.$value->id.'">';
                                echo '<label for="requirements-'.$key.'">'.$requirement->getRequirementsPart($value->id_req, 'name').' (aprobado)</label></br><hr>';
                            }
                        }
                    } ?>    
                </div>
                <input type="submit" name="approve" value="Accept">
            </form>
        <?php }else { ?>
             <form class="col-sm-4" action="" method="post">
                <h3>Requisitos Aprobados</h3>
                <div class="field">
                    <?php $arr_req = $requirement->getRequirements(Input::get('id'));
                    if ($arr_req) {
                        foreach ($arr_req as $key => $value) {
                            if ($value->approval == '0') {
                                echo '<label for="requirements-'.$key.'">'.$requirement->getRequirementsPart($value->id_req, 'name').' (aprobado)</label>';
                            }
                        }
                    } ?>    
                </div>
            </form>
        <?php } ?>
    </div>
<?} else { ?>
    <div class="row">
        <?
        if ($project->haveProject( $user->data()->id)) {
            echo "<div class='col-sm-12'><h2><a href='./'>Inicio</a></h2></div>";
            echo "<div class='col-sm-12'><h2>Proyectos Creados</h2></div>";
            if ($user->hasPermission('admin')) {
                $data_projects = $project->getProjects();
            }else {
                $data_projects = $project->getProjects( $user->data()->id);
            }
            foreach ($data_projects as $project) {
                $pro = new Project;
                echo "<div class='col-sm-6' style='padding-top: 15px; padding-bottom: 15px;'>";
                    echo "<div class='row'>";
                        echo "<div class='col-sm-6'>";
                            echo '<b>ID:</b> '.$project->id.'</br>';
                            echo '<b>NOMBRE:</b> '.$project->name.'</br>';
                            echo '<b>DESCRIPCIÓN:</b> '.$project->description.'</br>';
                        echo "</div>";
                        echo "<div class='col-sm-6'>";
                            echo "<p><b>N Requisitos:</b> ".$requirement->getRequirementsNum($project->id)."</br>";
                            echo $pro->getProjectLevel($project->id)."/5 ";
                            if ($pro->getProjectLevel($project->id) == 4 && $user->hasPermission('admin')) echo " - <a href='project.php?id=".$project->id."&approved=true'>Aprobar</a></br>";
                            else if($pro->getProjectLevel($project->id) == 5) echo ' - '.$pro->getProjectTime($project->id)."</br>";
                            else echo "</br>";
                            echo "<a href='project.php?id=".$project->id."'>Gestionar Requisitos</a></p>";
                        echo "</div>";
                    echo "</div>";
                echo "</div>";
            }
        }
        ?>
        <div class="clearfix"></div>
        <form class="col-sm-12" action="" method="post">
            <h2>Crear Nuevo</h2>
            <div class="field">
                <label for="name">Nombre</label>
                <br>
                <input type="text" name="name" id="name" value="<?php echo escape(Input::get('name')); ?>">    
            </div>
            <div class="field">
                <label for="description">Descripcion</label>
                <br>
                <textarea name="description" id="description" cols="30" rows="10"><?php echo escape(Input::get('description')); ?></textarea>   
            </div>
            <input type="submit" value="Register">
        </form>
    </div>
<?php }
get_template('footer');
