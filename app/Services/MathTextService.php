<?php

namespace App\Services;

class MathTextService
{
    public function render(mixed $value): string
    {
        $text = trim((string) $value);

        if ($text === '') {
            return '';
        }

        if ($this->looksLikeHtml($text)) {
            return $text;
        }

        $normalized = $this->stripFormattingWrappers($text);

        if ($this->looksLikeLatex($normalized)) {
            return '$' . $normalized . '$';
        }

        return e($text);
    }

    private function looksLikeHtml(string $text): bool
    {
        return str_contains($text, '<') && str_contains($text, '>');
    }

    private function looksLikeLatex(string $text): bool
    {
        if (str_contains($text, '$')) {
            return false;
        }

        return (bool) preg_match('/(\\\\[a-zA-Z]+|\\^|_)/', $text);
    }

    private function stripFormattingWrappers(string $text): string
    {
        $pattern = '/^\\\\(?:textit|textbf|mathrm|mathbf|mathit|mathtt|text)\{(.+)\}$/s';
        $previous = null;

        while ($previous !== $text) {
            $previous = $text;

            if (preg_match($pattern, $text, $matches)) {
                $text = trim($matches[1]);
            }
        }

        return $text;
    }
}
