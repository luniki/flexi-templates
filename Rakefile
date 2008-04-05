require 'rake/clean'

SRC = FileList['lib/**/*']
CLEAN.include('doc')

file 'doc' => SRC do
  sh "phpdoc --sourcecode on -t `pwd`/doc -d `pwd`/lib -ti 'trails documentation' -o 'HTML:frames:earthli'"
end

task 'test' do
  sh "php test/all_tests.php"
end
