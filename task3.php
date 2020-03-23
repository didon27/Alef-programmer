<?

$input = '->11гe+20∆∆A+4µcњil->5•Ћ®†Ѓ p+5f-7Ќ¬f pro+10g+1悦ra->58->44m+1*m+2a喜er!';

class Decoder
{
    private $position   = 0;
    private $output     = '';

    private $state;

    private const STATE_FREE        = 'processFree';
    private const STATE_MINUS       = 'processMinus';
    private const STATE_READ_NUMBER = 'processReadNumber';

    private const METHOD_GO         = 'goStack';
    private const METHOD_SKIP_RIGHT = 'skipStackRight';
    private const METHOD_SKIP_LEFT  = 'skipStackLeft';

    private $stack;
    private $method;

    public function decode(string $input): string
    {
        $this->position = 0;
        $this->output   = '';
        $this->state    = self::STATE_FREE;

        $length = mb_strlen($input, 'UTF-8');

        while ($this->position < $length) {
            $symbol = mb_substr($input, $this->position, 1, 'UTF-8');
            $this->{$this->state}($symbol);
        }

        return $this->output;
    }

    private function processFree(string $symbol): void
    {
        switch ($symbol) {
            case '-':
                $this->state = self::STATE_MINUS;
                break;
            case '+':
                $this->method = self::METHOD_SKIP_RIGHT;
                $this->state = self::STATE_READ_NUMBER;
                break;
            default:
                $this->output .= $symbol;
                break;
        }

        $this->position++;
    }

    private function processMinus(string $symbol): void
    {
        if ($symbol === '>') {
            $this->method   = self::METHOD_GO;
            $this->state    = self::STATE_READ_NUMBER;
            $this->position++;
            return;
        }

        $this->method = self::METHOD_SKIP_LEFT;
        $this->state = self::STATE_READ_NUMBER;
    }

    private function processReadNumber(string $symbol): void
    {
        if ($this->isNumber($symbol)) {
            $this->stack .= $symbol;
            $this->position++;
            return;
        }

        if ($this->stack === null) {
            throw new \InvalidArgumentException('Method has\'t number argument at position: ' . $this->position);
        }

        $this->{$this->method}((int) $this->stack);
        $this->stack    = null;
        $this->method   = null;
        $this->state    = self::STATE_FREE;
    }

    private function isNumber(string $symbol): bool
    {
        return !!preg_match('#[0-9]#', $symbol);
    }

    private function goStack(int $arg): void
    {
        $this->position = $arg;
    }

    private function skipStackRight(int $arg): void
    {
        $this->position += $arg;
    }

    private function skipStackLeft(int $arg): void
    {
        $this->position -= $arg;
    }
}


$out = (new Decoder())->decode($input);
echo $out;
