#!/usr/bin/env php
<?php declare(strict_types=1);
  
class HtmlRecursiveFilterIterator extends RecursiveFilterIterator {

    private static $FILTERS = array(
        '.html',
    );

    public function accept() {
         
            var_dump($this->current());

            echo "Extension is: " . $this->current()->getExtension() . "\n";

            if ($this->current()->getExtension() == "html") {
              echo "Extension is html\n";
              return true;
            } else return false;
/*
        return in_array(
            $this->current()->getFilename(),
            self::$FILTERS,
            true
        );
*/
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

 $filterIter = new \HtmlRecursiveFilterIterator($dirIter);

 $iter       = new RecursiveIteratorIterator($filterIter, RecursiveIteratorIterator::SELF_FIRST);


// foreach ($iter as $filePath => $fileInfo) {
   foreach ($iter as $filePath => $fileInfo) {
     echo 'Path name = ' .  $fileInfo->getPathName() . PHP_EOL;
     echo '   File name = ' . $fileInfo->getFilename() . PHP_EOL;
 }
