<?php
include './includes/DatabaseConnection.php';
include './includes/DatabaseFunctions.php';

// Get all modules from the database
$modules = getAllModules();

// Check if a module is selected
$selectedModulePosts = [];
if (isset($_POST['module_id'])) {
    $selectedModuleId = intval($_POST['module_id']);
    $selectedModulePosts = getPostsByModuleId($selectedModuleId);
}

// Store the data in a variable to be passed to the view
$data = [
    'modules' => $modules,
    'selectedModulePosts' => $selectedModulePosts,
    'selectedModuleId' => isset($selectedModuleId) ? $selectedModuleId : null
];

// Include the view file
include './admin_templates/module.html.php';
?>