<?php

/**
 * Template Begin
 * 
 * This File is included before any other text. Used by almost all modules. 
 * 
 * Used variables:
 * 
 * $I_TITLE  Web Title. As default displays $I_TITLE var.
 * $TEMPLATE_DIR  template directory set on config.php
 * 
 * $_YAPIG_LANG = language id (en, es, fr, ge) Set on locale.php
 * $_YAPIG_CHARSET = charset encoding (ISO-8859-1,ISO-8859-2) This data
 * is stored on the language .po file
 * 
 * @package template
 */

echo<<<FACE_BEGIN
<!DOCTYPE html
  PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="$_YAPIG_LANG" lang="$_YAPIG_LANG" >
<head>
<!-- face_begin BEGIN -->	
 <meta http-equiv="Content-Type" content="text/html; charset=$_YAPIG_CHARSET" />
 <title>{$I_TITLE}</title>
<!--<link rel="icon" href="{$TEMPLATE_DIR}mini.png" type="image/png" /> -->
<!-- METAS -->
<meta name="author" content="NaTaSaB" />
<meta name="description" content="YaPig, yet another PHP image gallery" />
<meta name="keywords" content="Yapig, gallery, php, script" />
<meta name="Copyright" content="NaTaSaB, Distributed under GPL" />
<!-- Metadatas Dublin Core -->
<meta name="DC.Title" content="{$I_TITLE}" />
<meta name="DC.Creator" content="NaTaSaB" />
<meta name="DC.Subject" content="Yet Another PHP image Gallery" />
<meta name="DC.Description" content="" />
<meta name="DC.Publisher" content="yapig.sourceforge.net" />
<meta name="DC.Date" content="2003-11-01" />
<meta name="DC.Relation.isPartOf" content="" />
<meta name="DC.Identifier" content="Yapig" />
<meta name="DC.Language" content="$_YAPIG_LANG" />
<meta name="DC.Rights" content="(c) NaTaSaB, Distributed under GPL" />
 <link rel="stylesheet" href="{$TEMPLATE_DIR}gallery.css" type="text/css" />	<script src="{$TEMPLATE_DIR}javascript.js" type="text/javascript"></script>
</head>		
<body>	
<!-- face_begin END -->  
FACE_BEGIN;
?>
