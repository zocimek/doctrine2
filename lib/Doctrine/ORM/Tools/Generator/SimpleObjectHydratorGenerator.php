<?php
/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license. For more information, see
 * <http://www.doctrine-project.org>.
 */

namespace Doctrine\ORM\Tools\Generator;

use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Mapping\ClassMetadataFactory;
use Doctrine\DBAL\Platforms\AbstractPlatform;

use Twig_Loader_Filesystem;
use Twig_Environment;

class SimpleObjectHydratorGenerator
{
    private $platform;
    private $metadataFactory;

    public function __construct(AbstractPlatform $platform, ClassMetadataFactory $metadataFactory)
    {
        $this->platform = $platform;
        $this->metadataFactory = $metadataFactory;
    }

    public function generate(ResultSetMapping $rsm)
    {
        if (count($rsm->aliasMap) !== 1) {
            throw new \RuntimeException("Cannot use SimpleObjectHydrator with a ResultSetMapping that contains more than one object result.");
        }

        if ($rsm->scalarMappings) {
            throw new \RuntimeException("Cannot use SimpleObjectHydrator with a ResultSetMapping that contains scalar mappings.");
        }

        $classMetadata = $this->metadataFactory->getMetadataFor(reset($rsm->aliasMap));

        $loader = new Twig_Loader_Filesystem(__DIR__ . '/Resources');
        $twig = new Twig_Environment($loader, array());
        $twig->addFilter('var_dump', new \Twig_Filter_Function('var_dump'));

        echo $twig->render('simple_object_hydrator.php.twig', array(
            'classMetadata' => $classMetadata,
            'ClassPrefix' => str_replace("\\", "", $classMetadata->name),
            'rsm' => $rsm,
            'platform' => $this->platform,
        ));
    }
}
