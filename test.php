#!/usr/bin/env php
<?php declare(strict_types=1);

/*
This code never finds the notes subdir of _/build/html
*/  
class HtmlRecursiveFilterIterator extends RecursiveFilterIterator {

    private static $FILTERS = array(
        '.html',
    );
/*
    public function accept() 
    {
            echo "In accept() getPath()/getFilename() = " . $this->current()->getPath() . '/' . $this->current()->getFilename() . PHP_EOL;   

            if ($this->current()->getExtension() == "html") {
              return true;
            } else
              return false;


    }
*/
    public function accept() 
    {
        $file = parent::current();

        if ($file->isDir()) 
              return true;
        
        $name = $file->getFilename();

        return (substr($name, -5) == '.html');
    }
}
 
 $dir = "./_build/html";

 if (!is_dir($dir)) {
    echo $dir . " does not exist\n";
    return;
 }
/*
 $dirIter    = new RecursiveDirectoryIterator($dir);

 $filterIter = new \HtmlRecursiveFilterIterator($dirIter);

 $itr       = new RecursiveIteratorIterator($filterIter); //, RecursiveIteratorIterator::SELF_FIRST);
*/
 $dirIter    = new RecursiveDirectoryIterator($dir);

 //$filterIter = new \HtmlRecursiveFilterIterator($dirIter);

 //$iter       = new RecursiveIteratorIterator($filterIter, RecursiveIteratorIterator::SELF_FIRST);
$iter       = new RecursiveIteratorIterator($dirIter, RecursiveIteratorIterator::SELF_FIRST);

   foreach ($iter as $filePath => $fileInfo) {
     echo 'Path name without file-name = ' .  $fileInfo->getPath() . PHP_EOL;
     echo '   File name = ' . $fileInfo->getFilename() . PHP_EOL;
 }
