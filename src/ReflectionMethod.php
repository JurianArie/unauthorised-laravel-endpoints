<?php

declare(strict_types=1);

namespace JurianArie\UnauthorisedDetection;

class ReflectionMethod extends \ReflectionMethod
{
    public function source(): string
    {
        $startLine = $this->getStartLine() - 1;
        $endLine = $this->getEndLine();
        $length = $endLine - $startLine;

        $source = file($this->getFileName());

        return implode('', array_slice($source, $startLine, $length));
    }
}
