<?php
/*
 * This file is part of the Guanaco package.
 *
 * (c) Alexandre Andre <alexandre@creakiwi.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Guanaco\Development\Domain\Service;

use Guanaco\Development\Domain\Command\GenerateCommandInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @author Alexandre Andre <alexandre@creakiwi.com>
 */
final class InitializePackageService
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var array
     */
    private $configuration;

    public function __construct(array $configuration)
    {
        $this->filesystem = new Filesystem();
        $this->configuration = $configuration;
    }

    public function handle(GenerateCommandInterface $command)
    {
        $this->makeDirectories(array_merge([$command->getPackageDirectory()], $command->getDirectories()));
        $this->createComposerFile($command);
        $this->createReadmeFile($command);
        $this->populateRootGitIgnoreFile($command);

        switch ($command->getType()) {
            case GenerateCommandInterface::TYPE_API:
            case GenerateCommandInterface::TYPE_INFRASTRUCTURE:
                $this->createBundleFile($command);
                break;
            default:
                break;
        }
    }

    private function makeDirectories(array $directories): void
    {
        $directories = array_map(function($directory) {
            return sprintf('%s/%s/', $this->configuration['root_directory'], $directory);
        }, $directories);
        $this->filesystem->mkdir($directories, 0755);
        $this->createGitIgnoreFiles($directories);
    }

    private function createGitIgnoreFiles(array $directories): void
    {
        $gitIgnoreFiles = array_map(function($directory) {
            return sprintf('%s/.gitignore', $directory);
        }, $directories);

        $this->filesystem->touch($gitIgnoreFiles);
        $this->filesystem->chmod($gitIgnoreFiles, 0644);
    }

    private function buildComposerRequirements(array $requirements)
    {
        $composerRequirements = [];

        foreach ($requirements as $requirement) {
            $composerRequirements[$requirement['package']] = $requirement['version'];
        }

        return $composerRequirements;
    }

    private function createComposerFile(GenerateCommandInterface $command): void
    {
        $path = sprintf('%s/%s/composer.json', $this->configuration['root_directory'], $command->getPackageDirectory());
        $composerConfiguration = [
            'name' => 'guanaco/' . strtolower($command->getPackageName()),
            'type' => $command->getPackageType(),
            'description' => '',
            'keywords' => ['guanaco', $command->getPackageType()],
            'homepage' => 'https://guanaco.com/',
            'license' => $this->configuration['composer']['license'],
            'authors' => $this->configuration['authors'],
            'require' => [
                'php' => '^7.2.28',
            ],
            'require-dev' => [
                'dg/bypass-finals' => '^1.1',
                'phpspec/prophecy' => '^1.10',
                'symfony/test-pack' => '^1.0',
                'symfony/var-dumper' => '^5.0',
            ],
            'config' => [
                'preferred-install' => [
                    '*' => 'dist',
                    'sort-packages' => true,
                ],
            ],
            'autoload' => [
                'psr-4' => [
                    sprintf('Guanaco\\%s\\%s\\', $command->getPackageName(), $command->getType()) => '',
                ],
            ],
            'autoload-dev' => [
                'psr-4' => [
                    sprintf('Guanaco\\%s\\%s\\Tests\\', $command->getPackageName(), $command->getType()) => 'Tests/',
                ],
            ],
            'minimum-stability' => 'dev',
            'prefer-stable' => true,
            'repositories' => [
                [
                    'type' => 'path',
                    'url' => '../../*/*',
                ],
            ],
            'extra' => [],
        ];

        $composerConfiguration['require'] += $this->buildComposerRequirements($this->configuration['composer']['requirements']['require']);
        $composerConfiguration['require-dev'] += $this->buildComposerRequirements($this->configuration['composer']['requirements']['require_dev']);

        ksort($composerConfiguration['require']);
        ksort($composerConfiguration['require-dev']);

        $this->generateFile($path, json_encode($composerConfiguration, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }

    private function createReadmeFile(GenerateCommandInterface $command): void
    {
        $path = sprintf('%s/%s/README.md', $this->configuration['root_directory'], $command->getPackageDirectory());
        $content = <<<MD
# {$command->getPackageName()}
MD;

        $this->generateFile($path, $content);
    }

    private function populateRootGitIgnoreFile(GenerateCommandInterface $command): void
    {
        $path = sprintf('%s/%s/.gitignore', $this->configuration['root_directory'], $command->getPackageDirectory());
        $content = <<<GITIGNORE
vendor/
bin/

composer.phar
composer.lock
GITIGNORE;

        $this->filesystem->appendToFile($path, $content);
    }

    private function getPhpFileHeaders()
    {
        return <<<PHP_FILE_HEADERS
<?php
/*
 * This file is part of the Guanaco package.
 *
 * (c) Alexandre Andre <alexandre@creakiwi.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

PHP_FILE_HEADERS;
    }

    private function getAuthorsAnnotation(): string
    {
        $authors = [];
        foreach ($this->configuration['authors'] as $author) {
            $authors[] = sprintf(' * @author %s <%s>', $author['name'], $author['email']);
        }

        return "/**\n".implode("\n", $authors)."\n */";
    }

    private function createBundleFile(GenerateCommandInterface $command): void
    {
        $phpFileHeaders = $this->getPhpFileHeaders();
        $namespace = $this->configuration['namespace'];
        $packageName = $command->getPackageName();
        $packageType = $command->getType();
        $authors = $this->getAuthorsAnnotation();
        $path = sprintf('%s/%s/Guanaco%s%sBundle.php', $this->configuration['root_directory'], $command->getPackageDirectory(), $command->getPackageName(), $command->getType());
        $content = <<<BUNDLE
${phpFileHeaders}
namespace ${namespace}\\${packageName}\\${packageType};

use ${namespace}\\${packageName}\\${packageType}\DependencyInjection\\${namespace}${packageName}${packageType}Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

${authors}
final class ${namespace}${packageName}${packageType}Bundle extends Bundle
{
    public function build(ContainerBuilder \$container)
    {
        parent::build(\$container);
    }

    public function getContainerExtension()
    {
        return new ${namespace}${packageName}${packageType}Extension();
    }
}
BUNDLE;

        $this->filesystem->appendToFile($path, $content);
    }

    private function generateFile(string $path, string $content)
    {
        if ($this->filesystem->exists($path)) {
            return;
        }

        $this->filesystem->dumpFile($path, $content);
    }
}
