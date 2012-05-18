<?php
namespace Netresearch\Source;

use Netresearch\Source\Git;

abstract class Base
{
    protected $source;

    /**
     * set source to new instance
     *
     * @param string $source
     * @return Base
     */
    public function __construct($source)
    {
        $this->source = $source;
    }

    public static function isGitRepo($repoUrl)
    {
        return (
            (0 === strpos($repoUrl, 'git@')) // path starts with "git@"
            || (0 === strpos($repoUrl, 'git://')) // path starts with "git://"
            || (0 === strpos($repoUrl, 'http://')) // path starts with "http://"
            || (0 === strpos($repoUrl, 'ssh://')) // path starts with "ssh://"
            || (is_dir($repoUrl . DIRECTORY_SEPARATOR . '.git')) // dir contains .git folder
        );
    }
    
    public static function isFilesystemPath($sourcePath)
    {
        return (0 === strpos($sourcePath, '/')); // path is absolute filesystem path
    }
    
    public static function isHttpUrl($sourceUrl)
    {
        return (0 === strpos($sourceUrl, 'http://')); // path is web path
    }
    
    /**
     * 
     * @param string $source
     */
    public static function getSourceModel($source)
    {
        if (self::isGitRepo($source)) {
            return new Git($source);
        } elseif (self::isFilesystemPath($source)) {
            return new Filesystem($source);
        } elseif (self::isHttpUrl($source)) {
            return new Http($source);
        }
        
        throw new Exception("No applicable source model found for source '$source'");
    }
}