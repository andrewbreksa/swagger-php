<?php declare(strict_types=1);

/**
 * @license Apache 2.0
 */

namespace OpenApi\Tests;

use Doctrine\Common\Annotations\AnnotationRegistry;
use OpenApi\Analyser;

class AnalyserTest extends OpenApiTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Analyser::$defaultImports['swg'] = 'OpenApi\Annotations';
    }

    protected function tearDown(): void
    {
        unset(Analyser::$defaultImports['swg']);
        parent::tearDown();
    }

    public function testParseContents()
    {
        $annotations = $this->parseComment('@OA\Parameter(description="This is my parameter")');
        $this->assertIsArray($annotations);
        $parameter = $annotations[0];
        $this->assertInstanceOf('OpenApi\Annotations\Parameter', $parameter);
        $this->assertSame('This is my parameter', $parameter->description);
    }

    public function testDeprecatedAnnotationWarning()
    {
        if(!method_exists(AnnotationRegistry::class, 'registerLoader')) {
            $this->markTestSkipped('Doctrine\Common\Annotations\AnnotationRegistry::registerLoader() is not available');
        }
        $this->assertOpenApiLogEntryContains('The annotation @SWG\Definition() is deprecated.');
        $this->parseComment('@SWG\Definition()');
    }
}
