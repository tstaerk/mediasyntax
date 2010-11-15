<?php
// dokuwiki2mediawiki.php (c) 2010 by Thorsten Staerk
// This program reads files containing dokuwiki syntax and converts them into files
// containing mediawiki syntax.
// The source file is given by parameter, the target file is the source file plus a
// ".mod" suffix.

// TODO:
// filename: not only one file name!
// read: not only 4096 bytes
// test if we overwrite a file
// test if file exists

if ($argc==1)
{
  echo "dokuwiki2mediawiki.php (c) 2010 by Thorsten Staerk\n";
  echo "This program converts dokuwiki syntax to mediawiki syntax.\n";
  echo "The source file is given as an argument, the target file is the same plus the suffix \".mod\"\n";
  echo "Usage: php dokuwiki2mediawiki <file>\n";
  echo "Example: php dokuwiki2mediawiki start.txt\n";
}
else
for ($argument=1;$argument<$argc;$argument++)
{
  $filename=$argv[$argument];
  $inputfile=fopen($filename,"r");
  $outputfile=fopen($filename.".mod","w");
  if ($inputfile) 
  {
    if ($outputfile)
    {
      while (!feof($inputfile))
      {
        $line=fgets($inputfile,4096);

        // replace headings
        if (preg_match('/^ *======.*====== *$/',$line))
        {
          $line=preg_replace('/^ *======/','=',$line);
          $line=preg_replace('/====== *$/','=',$line);
        }
        elseif (preg_match('/^ *=====.*===== *$/',$line))
        {
          $line=preg_replace('/^ *=====/','==',$line);
          $line=preg_replace('/===== *$/','==',$line);
        }
        elseif (preg_match('/^ *====.*==== *$/',$line))
        {
          $line=preg_replace('/^ *====/','===',$line);
          $line=preg_replace('/==== *$/','===',$line);
        }
        elseif (preg_match('/^ *===.*=== *$/',$line))
        {
          $line=preg_replace('/^ *===/','====',$line);
          $line=preg_replace('/=== *$/','====',$line);
        }
        elseif (preg_match('/^ *==.*== *$/',$line))
        {
          $line=preg_replace('/^ *==/','=====',$line);
          $line=preg_replace('/== *$/','=====',$line);
        }
        // end of replace headings

        // replace bulletpoints
        $level=0; // level of bulletpoints, e.g. * is level 1, *** is level 3.
        while (preg_match('/^(  )+\*/',$line))
        {
          $line=preg_replace("/^  /","",$line);
          $level++;
        }
        while ($level>1)
        {
          $line="*".$line;
          $level--;
        }
        // end of replace bulletpoints

        // replace ordered list items
        $level=0; // level of list items, e.g. - is level 1, --- is level 3.
        while (preg_match('/^(  )+\-/',$line))
        {
          $line=preg_replace("/^  /","",$line);
          $level++;
        }
        $line=preg_replace("/^-/","#",$line);
        while ($level>1)
        {
          $line="#".$line;
          $level--;
        }
        // end of replace ordered list items

        fwrite($outputfile,$line);
      }
    }
  }
  fclose ($inputfile);
  fclose ($outputfile);
}
?>