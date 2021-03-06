<?php
// This is borrowed from https://github.com/akoebbe/php_koans/blob/master/KoansLib/KoanPrinter.php
//
// Under MIT License
namespace KoansLib;

use PHPUnit\Framework\TestResult;
use PHPUnit\Util\TestDox\CliTestDoxPrinter;

/**
 * Class KoanPrinter
 *
 * Override the basic PHPUnit ResultPrinter to produce a more mindful output
 *
 * @package KoansLib
 */
class KoanPrinter extends CliTestDoxPrinter
{
    public function printResult(TestResult $result): void
    {
        $this->printHeader();

        $this->printFooter($result);
    }

    protected function printHeader(): void
    {
        $this->write("\n");
    }

    protected function printFooter(TestResult $result): void
    {
        if (\count($result) === 0) {
            $this->writeWithColor(
                'fg-black, bg-yellow',
                'No tests executed!'
            );

            return;
        }

        if ($result->wasSuccessful() &&
            $result->allHarmless() &&
            $result->allCompletelyImplemented() &&
            $result->noneSkipped()) {
            $this->writeWithColor(
                'fg-black, bg-green',
                \sprintf(
                    'You have traversed the mountain path. You have found peace. (%d koan%s)',
                    \count($result),
                    (\count($result) == 1) ? '' : 's',
                    $this->numAssertions,
                    ($this->numAssertions == 1) ? '' : 's'
                )
            );
        } else {
            if ($result->wasSuccessful()) {
                $color = 'bold,fg-black, bg-yellow';

                if ($this->verbose || !$result->allHarmless()) {
                    $this->write("\n");
                }

                $this->writeWithColor(
                    $color,
                    'OK, but incomplete, skipped, or risky tests!'
                );
            } else {
                $msgs = [
                    "mountains are merely mountains",
                    "learn the rules so you know how to break them properly",
                    "remember that silence is sometimes the best answer",
                    "sleep is the best meditation",
                    "when you lose, don't lose the lesson",
                    "things are not what they appear to be: nor are they otherwise"
                ];
                $this->write("\n");

                if ($result->errorCount()) {
                    $color = 'bold,fg-white, bg-red';

                    $this->writeWithColor(
                        $color,
                        $msgs[array_rand($msgs)] . PHP_EOL .
                        'Error: You have stumbled on your path, but you are capable of recovering!'
                    );
                } elseif ($result->failureCount()) {
                    $color = 'bold, fg-white, bg-blue';

                    $this->writeWithColor(
                        $color,
                        $msgs[array_rand($msgs)] . PHP_EOL .
                        'You are making progress but have more to learn. Contemplate the message above further.'
                    );
                } elseif ($result->warningCount()) {
                    $color = 'bold,fg-white, bg-blue';

                    $this->writeWithColor(
                        $color,
                        $msgs[array_rand($msgs)] . PHP_EOL .
                        'WARNINGS!'
                    );
                }
            }

            $this->writeCountString($this->numTests, 'Total Koans', $color, true);
            $this->writeCountString(count($result->passed()), 'Koans Completed', $color, true);
            $this->writeCountString($result->errorCount(), 'Errors', $color);
            $this->writeWithColor($color, '.');
        }
    }

    /**
     * Copied verbatim from ResultPrinter because it is private and the printFooter calls this.
     *
     * @param int $count
     * @param string $name
     * @param string $color
     * @param bool $always
     */
    private function writeCountString(int $count, string $name, string $color, bool $always = false): void
    {
        static $first = true;

        if ($always || $count > 0) {
            $this->writeWithColor(
                $color,
                \sprintf(
                    '%s%s: %d',
                    !$first ? ', ' : '',
                    $name,
                    $count
                ),
                false
            );

            $first = false;
        }
    }

}
