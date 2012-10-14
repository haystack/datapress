import os
import optparse
import sys

def bundle_version(version):
  plugin_path = os.path.split(os.getcwd())[0]
  file_stem = "datapress-%s" % (version)
  file_name = "%s.zip" % (file_stem)
  
  temp_dir = os.path.join(plugin_path, file_stem)
  if (os.path.exists(temp_dir)):
    print "Error: Dir exists"
    return
  
  os.system("cp -R %s %s" % (os.getcwd(), temp_dir)) 
  os.system("find %s -name .svn | xargs rm -rf" % (temp_dir)) 
  os.system("zip -r %s %s" % (os.path.join(plugin_path, file_name), temp_dir))
  os.system("rm -rf %s" % (temp_dir))

def main():
  parser = optparse.OptionParser(usage='datapress_bundle.py -v version ')
  parser.add_option('-v', '--version', dest='version',
                    help='Datapress version, e.g. 1.2.3')
                    
  options, args = parser.parse_args()
  if not options.version:
    parser.error('Datapress version is missing.')

  bundle_version(options.version)

if __name__ == '__main__':
  sys.exit(main())