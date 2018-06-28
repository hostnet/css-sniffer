<?php
/**
 * @copyright 2017 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\CssSniff\Sniff;

use Hostnet\Component\CssSniff\File;
use Hostnet\Component\CssSniff\SniffInterface;
use Yannickl88\Component\CSS\Token;

final class ColonSniff implements SniffInterface
{
    /**
     * @var string[]
     * @see https://developer.mozilla.org/en-US/docs/Web/CSS/Pseudo-classes
     */
    private const PSEUDO_CLASSES = [
        'active',
        'any',
        'any-link',
        'checked',
        'default',
        'dir',
        'disabled',
        'empty',
        'enabled',
        'first',
        'first-child',
        'first-of-type',
        'fullscreen',
        'focus',
        'focus-within',
        'hover',
        'indeterminate',
        'in-range',
        'invalid',
        'lang',
        'last-child',
        'last-of-type',
        'left',
        'link',
        'not',
        'nth-child',
        'nth-last-child',
        'nth-last-of-type',
        'nth-of-type',
        'only-child',
        'only-of-type',
        'optional',
        'out-of-range',
        'placeholder-shown',
        'read-only',
        'read-write',
        'required',
        'right',
        'root',
        'scope',
        'target',
        'valid',
        'visited',
    ];

    /**
     * @var string[]
     * @see http://lesscss.org/features/#extend-feature
     */
    private const PSEUDO_CLASSES_LESS = [
        'extend',
    ];

    /**
     * @var string[]
     * @see https://angular.io/guide/component-styles
     */
    private const PSEUDO_CLASSES_ANGULAR = [
        'host',
        'host-context',
    ];

    /**
     * List of options which can be configured.
     */
    private const PSEUDO_CLASS_OPTIONS = [
        'css' => self::PSEUDO_CLASSES,
        'less' => self::PSEUDO_CLASSES_LESS,
        'angular' => self::PSEUDO_CLASSES_ANGULAR,
    ];

    /**
     * @var string[]
     */
    private $pseudo_classes;

    public function __construct(string $pseudo_classes = 'css,less')
    {
        $this->pseudo_classes = array_merge([], ...array_map(function (string $class) {
            $class = trim($class);

            if (!isset(self::PSEUDO_CLASS_OPTIONS[$class])) {
                throw new \LogicException(sprintf(
                    'Unknown pseudo classes for "%s", options are: "%s".',
                    $class,
                    implode('", "', array_keys(self::PSEUDO_CLASS_OPTIONS))
                ));
            }

            return self::PSEUDO_CLASS_OPTIONS[$class];
        }, explode(',', $pseudo_classes)));
    }

    public function register(): array
    {
        return [
            Token::T_COLON,
        ];
    }

    public function process(File $file, int $stack_ptr): void
    {
        $next = $file->get($stack_ptr + 1);

        if ($next->type === Token::T_WHITESPACE && $next->chars === ' ') {
            return;
        }

        // ::after(), etc. so Pseudo-elements
        if ($stack_ptr > 0 && $file->get($stack_ptr - 1)->type === Token::T_COLON) {
            return;
        }

        // Check that it is indeed a property
        if (!$this->isProperty($file, $stack_ptr)) {
            return;
        }

        $t = $file->get($stack_ptr);

        $file->addViolation(
            self::class,
            'Colon should be followed by a single space.',
            $t->lines[0],
            $t->offsets[0],
            $t->offsets[0] + \strlen($t->chars)
        );
    }

    private function isProperty(File $file, int $stack_ptr): bool
    {
        $tokens = $file->getTokens();
        $found  = [];

        for ($i = $stack_ptr + 1, $n = count($tokens); $i < $n; $i++) {
            if ($tokens[$i]->type === Token::T_WHITESPACE) {
                continue;
            }

            $allowed_tokens = [
                Token::T_WORD,
                Token::T_OPENBRACKET,
                Token::T_OPENSQUARE,
                Token::T_CLOSESQUARE,
                Token::T_ATWORD,
            ];

            if (\in_array($tokens[$i]->type, $allowed_tokens, true)) {
                $found[] = $tokens[$i];
                continue;
            }

            if (\in_array($tokens[$i]->type, [Token::T_SEMICOLON, Token::T_CLOSEBRACKET], true)) {
                $found[] = $tokens[$i];
                return !$this->isModifier($found);
            }
            break;
        }

        return false;
    }

    /**
     * @param Token[] $tokens
     * @return bool
     */
    private function isModifier(array $tokens): bool
    {
        $first = $tokens[0];
        $last  = $tokens[count($tokens) - 1];

        if ($last->type === Token::T_SEMICOLON) {
            return false;
        }

        return $first->type === Token::T_WORD && \in_array($first->chars, $this->pseudo_classes, true);
    }
}
