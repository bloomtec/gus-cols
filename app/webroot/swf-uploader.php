<?php
	/*
	Uploadify
	Copyright (c) 2012 Reactive Apps, Ronnie Garcia
	Released under the MIT License <http://www.opensource.org/licenses/mit-license.php>
	*/

	// Define a destination
	$targetFolder = '/app/webroot/files/' . $_POST['contentType'] . '/' . $_POST['directory']; // Relative to the root

	$verifyToken = md5('8206092654127895411132256' . $_POST['timestamp']);

	if(!empty($_FILES) && $_POST['token'] == $verifyToken) {
		$tempFile   = $_FILES['Filedata']['tmp_name'];
		$targetPath = $_SERVER['DOCUMENT_ROOT'] . $targetFolder;
		$targetFile = rtrim($targetPath, '/') . '/' . $_FILES['Filedata']['name'];
		if(move_uploaded_file($tempFile, $targetFile)) {
			echo 1;
		} else {
			echo 0;
		}
		/**
		// Validate the file type
		$fileTypes = array('jpg','jpeg','gif','png'); // File extensions
		$fileParts = pathinfo($_FILES['Filedata']['name']);

		if (in_array($fileParts['extension'],$fileTypes)) {
		move_uploaded_file($tempFile,$targetFile);
		echo '1';
		} else {
		echo 'Invalid file type.';
		}
		 */
	}
?>