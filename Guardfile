# A sample Guardfile
# More info at https://github.com/guard/guard#readme

guard 'phpunit', :cli => '--colors', :tests_path => 'tests' do
  watch(%r{^.+Test\.php$})

  watch(%r{Sinergia/(.+).php}) {|m| "tests/#{m[1]}Test.php"}
end
