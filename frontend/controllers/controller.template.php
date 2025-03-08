<?php

class TemplateController
{
  //MAIN VIEW TEMPLATE
  public function index()
  {
    include 'views/template.php';
  }

  //main route url
  static public function path()
  {
    return "http://" . $_SERVER['SERVER_NAME'] . "/automatitation-front-end/";
  }
}

