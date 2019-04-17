<?php
namespace Project\Storage;

use Packaged\Dal\Ql\QlDao;

class ContentPage extends QlDao
{
  protected $_dataStoreName = 'cubex_skeleton';

  public $id;
  public $title;
  public $content;
}
