<?php

namespace hpom;

class MetricParser
{
    private $raw;
    private $i = 0;

    /**
     * HpomMetricTokenizer constructor.
     *
     * $rawMetric must be a string contains value
     * of 'policy_body' field of 'opc_op.opc_policy_body' table
     *
     * @param string $rawMetric
     */
    public function __construct($rawMetric) {
        $this->raw = explode("\n", $rawMetric);
    }

    /**
     * @return array
     */
    public function parse(): array {
        $tail     = [];
        $firstTag = false;
        $isArray  = false;
        for (; isset($this->raw[ $this->i ]); $this->i++) {
            list($level, $tag, $value) = $this->parseRow($this->raw[ $this->i ]);
            if (empty($tag)) {
                continue;
            }
            $value  = $this->prepareValue($value);
            $nLevel = $this->nextLevel();

            if ($tag === $firstTag) {
                $isArray = true;
                $this->collect($tail);
            }
            $this->add($tail, $tag, $value);

            if ($nLevel > $level) {
                $this->i++;
                $this->add($tail, $tag, $this->parse());
                $nLevel = $this->nextLevel();
            }

            if ($nLevel < $level) {
                break;
            }
            if ($firstTag === false) {
                $firstTag = $tag;
            }
        }
        if ($isArray) {
            $this->collect($tail);
        }

        return $tail;
    }

    private function add(array &$tail, $tag, $value) {
        if (is_scalar($value)) {
            $value = trim($value, '"');
        }
        if (!isset($tail[ $tag ])) {
            $tail[ $tag ] = $value;

            return;
        }
        if (!\is_array($tail[ $tag ])) {
            $tail[ $tag ] = ['_value' => $tail[ $tag ]];
        } else if (!isset($tail[ $tag ][0])) {
            $tail[ $tag ] = [$tail[ $tag ]];
        }
        $tail[ $tag ][] = $value;
    }

    private function collect(array &$array) {
        $out  = [];
        $temp = [];
        foreach ($array as $key => $value) {
            if (is_numeric($key)) {
                $out[] = $value;
                continue;
            }
            if ($key === '_value') {
                $out['_value'] = $value;
                continue;
            }
            $temp[ $key ] = $value;
        }
        $out[] = $temp;
        $array = $out;
    }

    private function nextLevel(): int {
        return isset($this->raw[ $this->i + 1 ])
            ? \strlen($this->raw[ $this->i + 1 ]) - \strlen(ltrim($this->raw[ $this->i + 1 ]))
            : -1;
    }

    private function parseRow($string): array {
        $out   = ltrim($string);
        $level = \strlen($string) - \strlen($out);
        $tag   = trim(strstr($out . ' ', ' ', true));
        $value = trim(strstr($out, ' '));

        return [$level, $tag, $value];
    }

    private function prepareValue($value): string {
        if (substr_count(str_replace('\\"', '', $value), '"') % 2 === 0) {
            return $value;
        }

        do {
            $line  = rtrim($this->raw[ ++$this->i ]);
            $value .= "\n" . $line;
        } while (
            isset($this->raw[ $this->i + 1 ])
            && $line !== '"'
            && (substr($line, -1) !== '"' || substr($line, -2) === '\\"'));

        return $value;
    }
}