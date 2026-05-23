// thanks chatgpt

<?php
declare(strict_types=1);

/**
 * PHP Feature Tour — requires PHP 8.2+
 * Run: php feature_tour.php
 */

//////////////////////////////
// 0) Basics & constants
//////////////////////////////

define('APP_NAME', 'FeatureTour');
const VERSION = '1.0';
echo "== ".APP_NAME." v".VERSION." ==\n";

echo "Magic: file=".__FILE__.", dir=".__DIR__.", line=".__LINE__."\n";

//////////////////////////////
// 1) Scalars, arrays, operators
//////////////////////////////

$int = 42;
$float = 3.14;
$bool = true;
$str  = "hello";
$nowdoc = <<<'TXT'
nowdoc: $not_interpolated
TXT;
$heredoc = <<<TXT
heredoc: interpolated: {$str}
TXT;

echo $nowdoc."\n".$heredoc."\n";

$indexed = [1, 2, 3];
$assoc   = ['a' => 10, 'b' => 20];
$mixed   = [...$indexed, ...array_values($assoc)]; // unpacking
[$first, , $third] = $indexed;                     // array destructuring
['a' => $A] = $assoc;

$nullable = null;
$nullable ??= 'defaulted via ??=';
$tern = $bool ? 'yes' : 'no';
$elvis = $str ?: 'fallback';
$cmp = 7 <=> 4;

$concat = 'foo';
$concat .= 'bar';

echo "Destructured: first=$first third=$third A=$A; cmp=$cmp; concat=$concat; nullish=$nullable; tern=$tern; elvis=$elvis\n";

//////////////////////////////
// 2) Control structures
//////////////////////////////

if ($int > 0) {
    echo "if/elseif/else: positive\n";
} elseif ($int < 0) {
    echo "negative\n";
} else {
    echo "zero\n";
}

$val = 2;
switch ($val) {
    case 1:
        echo "switch: one\n"; break;
    case 2:
    case 3:
        echo "switch: two or three\n"; break;
    default:
        echo "switch: other\n";
}

$fruit = 'apple';
$result = match ($fruit) {
    'apple', 'pear' => 'pome',
    'banana'        => 'berry',
    default         => 'unknown',
};
echo "match: $fruit is $result\n";

for ($i = 0; $i < 3; $i++) { if ($i === 2) { continue; } echo "for:$i "; } echo "\n";
$i = 0; while ($i < 2) { echo "while:$i "; $i++; } echo "\n";
$j = 0; do { echo "do:$j "; $j++; } while ($j < 1); echo "\n";

// goto (rarely recommended, but a language feature)
goto SKIP_LABEL_DEMO_END;
LABEL_DEMO:
echo "You won't see this";
SKIP_LABEL_DEMO_END:

//////////////////////////////
// 3) Functions & closures
//////////////////////////////

function add(int|float $a, int|float $b = 0): int|float { return $a + $b; }
function variadic_sum(int ...$xs): int { $s = 0; foreach ($xs as $x) { $s += $x; } return $s; }
function uses_named_args(string $a, string $b, string $c): string { return "$a-$b-$c"; }
function byref_increment(int &$x): void { $x++; }
function returns_never(): never { throw new RuntimeException("Demonstrating never via exception"); }

$sum = add(2, 3);
$varsum = variadic_sum(...[1,2,3,4]);

$named = uses_named_args(b: 'B', a: 'A', c: 'C');

$x = 5; byref_increment($x);

$arrow = fn(int $n) => $n * 2;

$outer = 10;
$closure = function(int $n) use ($outer) { return $n + $outer; };

echo "funcs: sum=$sum varsum=$varsum named=$named byref=$x arrow=".$arrow(7)." closure=".$closure(5)."\n";

//////////////////////////////
// 4) Generators
//////////////////////////////

function gen_range(int $start, int $end): Generator {
    for ($i = $start; $i <= $end; $i++) yield $i;
}
function gen_composed(): Generator {
    yield from gen_range(1, 3);
    yield 'done';
}
echo "generator: ";
foreach (gen_composed() as $v) { echo "$v "; }
echo "\n";

//////////////////////////////
// 5) Namespaces, interfaces, traits, classes
//////////////////////////////

namespace Features {
    interface Loggable { public function log(): string; }
    interface Jsonable { public function toJson(): string; }

    trait IdTrait {
        public function id(): string { return spl_object_hash($this); }
    }

    trait LoggerTrait {
        public function log(): string { return static::class."#".$this->id(); }
    }

    trait ConflictingA { public function say(): string { return 'A'; } }
    trait ConflictingB { public function say(): string { return 'B'; } }

    abstract class AbstractThing implements Loggable, Jsonable {
        use IdTrait, LoggerTrait;
        // typed properties, property promotion, default, readonly (8.1+ for prop)
        public function __construct(
            protected string $name,
            public readonly int $version = 1
        ) {}
        abstract public function kind(): string;
        public function toJson(): string { return json_encode(['k'=>$this->kind(),'n'=>$this->name,'v'=>$this->version], JSON_THROW_ON_ERROR); }
        public static function who(): string { return static::class; } // late static binding
    }

    class Thing extends AbstractThing {
        use ConflictingA, ConflictingB {
            ConflictingA::say insteadof ConflictingB;
            ConflictingB::say as sayB;
        }
        public const TYPE = 'thing';
        private string $hidden = 'secret';
        public function __construct(string $name, public int $count = 0) {
            parent::__construct($name, version: 2); // named arg into parent ctor
        }
        public function kind(): string { return self::TYPE; }
        // Magic methods (selection):
        public function __toString(): string { return "{$this->name}({$this->count})"; }
        public function __get(string $prop): mixed { return $prop === 'exposed' ? 'via __get' : null; }
        public function __set(string $prop, mixed $val): void { if ($prop === 'hidden') { $this->hidden = (string)$val; } }
        public function __isset(string $prop): bool { return $prop === 'exposed'; }
        public function __unset(string $prop): void { /* noop */ }
        public function __invoke(string $x): string { return "invoked:$x"; }
        public function __clone() { $this->count = 0; }
        public function __debugInfo(): ?array { return ['name'=>$this->name, 'count'=>$this->count, 'id'=>$this->id()]; }
        public function __serialize(): array { return ['n'=>$this->name, 'c'=>$this->count]; }
        public function __unserialize(array $data): void { $this->name = $data['n']; $this->count = $data['c']; }
        public static function __callStatic($name, $args) { return "static call: $name(".count($args).")"; }
        public function __call($name, $args) { return "instance call: $name(".count($args).")"; }
    }

    // Anonymous class
    function makeCounter(): object {
        return new class {
            private int $n = 0;
            public function next(): int { return ++$this->n; }
        };
    }

    // Enums (8.1+)
    enum Status: string {
        case New = 'new';
        case Done = 'done';
        public function isFinal(): bool { return $this === self::Done; }
        public static function fromFlag(bool $b): self { return $b ? self::Done : self::New; }
    }

    // Attributes (8.0+)
    #[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD | \Attribute::TARGET_PROPERTY)]
    class Meta {
        public function __construct(public string $tag, public array $data = []) {}
    }

    #[Meta('entity', ['table' => 'things'])]
    class Tagged {
        #[Meta('prop', ['persist' => true])]
        public string $field = 'value';

        #[Meta('method')]
        public function hello(): string { return 'hello'; }
    }
}

namespace {
    use Features\Thing;
    use Features\AbstractThing;
    use Features\Status;
    use Features\Tagged;
    use Features\Meta;

    $t = new Thing('widget', count: 3);
    echo "class: $t, kind=".$t->kind().", say=".$t->say().", sayB=".$t->sayB()."\n";
    echo "magic __get exposed? ".($t->exposed ?? 'null')."\n";
    echo "invoke: ".$t('ok')."\n";
    echo "late static binding: ".AbstractThing::who()." vs ".Thing::who()."\n";
    $t2 = clone $t;
    echo "clone resets count: $t2\n";
    echo "dynamic call: ".$t->nope(1,2)."; static call: ".Thing::nope(1)."\n";

    $anon = Features\makeCounter();
    echo "anon class: ".$anon->next().", ".$anon->next()."\n";

    $s = Status::fromFlag(true);
    echo "enum: ".$s->value.", isFinal=".($s->isFinal() ? 'yes':'no')."\n";

    // Reflection & attributes
    $rc = new ReflectionClass(Tagged::class);
    $attrs = array_map(fn($a)=>[$a->getName(), $a->getArguments()], $rc->getAttributes());
    echo "attributes on Tagged: ".json_encode($attrs)."\n";

    //////////////////////////////
    // 6) Nullsafe, errors, exceptions
    //////////////////////////////

    $maybe = null;
    $res = $maybe?->nonexistent()?->deep ?? 'nullsafe fell back';
    echo "nullsafe: $res\n";

    set_error_handler(function($errno, $errstr) {
        throw new ErrorException($errstr, 0, $errno);
    });
    try {
        // trigger user warning -> converted to exception
        @trigger_error("custom warning", E_USER_WARNING);
    } catch (Throwable $e) {
        echo "caught error as exception: ".get_class($e).": ".$e->getMessage()."\n";
    } finally {
        restore_error_handler();
    }

    try {
        returns_never();
    } catch (RuntimeException $e) {
        echo "never-function threw as expected\n";
    }

    //////////////////////////////
    // 7) Regex, strings
    //////////////////////////////

    $email = "user@example.com";
    if (preg_match('/^[\w.+-]+@[\w.-]+\.[A-Za-z]{2,}$/', $email)) {
        echo "regex ok: $email\n";
    }
    echo preg_replace('/(foo)(bar)/', '$2$1', 'foobar')."\n";

    //////////////////////////////
    // 8) Files, streams, SPL
    //////////////////////////////

    $tmp = tempnam(sys_get_temp_dir(), 'feat_');
    file_put_contents($tmp, "line1\nline2\n");
    $fh = fopen($tmp, 'a+');
    fwrite($fh, "line3\n");
    rewind($fh);
    echo "file:\n".stream_get_contents($fh);
    fclose($fh);

    $sfo = new SplFileObject($tmp);
    $sfo->setFlags(SplFileObject::DROP_NEW_LINE);
    echo "SPL iter:\n";
    foreach ($sfo as $line) { echo "[$line]\n"; }
    unlink($tmp);

    //////////////////////////////
    // 9) Data/JSON, serialize, DateTime
    //////////////////////////////

    $data = ['k'=>'v','n'=>123,'arr'=>[1,2]];
    $json = json_encode($data, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR);
    echo "json:\n$json\n";
    $decoded = json_decode($json, true, 512, JSON_THROW_ON_ERROR);

    $ser = serialize($t);
    $t3 = unserialize($ser, ['allowed_classes'=>[Thing::class]]);
    echo "serialize/unserialize: $t3\n";

    $dt = new DateTime('now', new DateTimeZone('UTC'));
    echo "datetime: ".$dt->format(DateTimeInterface::ATOM)."\n";

    //////////////////////////////
    // 10) Superglobals & env (read-only examples)
    //////////////////////////////

    echo "argc/argv: ".(int)($_SERVER['argc'] ?? 0)."\n";
    echo "env PATH is set? ".(isset($_ENV['PATH']) || getenv('PATH') ? 'yes' : 'no')."\n";

    //////////////////////////////
    // 11) WeakMap (8.0+)
    //////////////////////////////

    $wm = new WeakMap();
    $obj = new stdClass();
    $wm[$obj] = 'attached';
    echo "WeakMap before unset: ".$wm[$obj]."\n";
    unset($obj);
    // Garbage collection might clear it later; demonstrate presence count
    echo "WeakMap count (>=0): ".count($wm)."\n";

    //////////////////////////////
    // 12) Interfaces & iterables (IteratorAggregate example)
    //////////////////////////////

    class Bag implements IteratorAggregate {
        public function __construct(private array $items) {}
        public function getIterator(): Traversable { yield from $this->items; }
    }
    $bag = new Bag([10,20,30]);
    echo "IteratorAggregate: ";
    foreach ($bag as $it) { echo "$it "; }
    echo "\n";

    //////////////////////////////
    // 13) Null coalesce chain & ??=
    //////////////////////////////

    $maybeA = null; $maybeB = null; $maybeC = 'C';
    $firstNonNull = $maybeA ?? $maybeB ?? $maybeC ?? 'Z';
    echo "coalesce chain: $firstNonNull\n";

    //////////////////////////////
    // 14) Pattern matching + enums combined
    //////////////////////////////

    $msg = match (Status::New) {
        Status::New  => 'fresh',
        Status::Done => 'finished',
    };
    echo "enum+match: $msg\n";

    //////////////////////////////
    // 15) Reflection on attributes (continued)
    //////////////////////////////

    $prop = (new ReflectionProperty(Tagged::class, 'field'));
    $pAttrs = array_map(fn($a)=>[$a->getName(), $a->getArguments()], $prop->getAttributes(Meta::class));
    echo "property attributes: ".json_encode($pAttrs)."\n";

    $meth = (new ReflectionMethod(Tagged::class, 'hello'));
    $mAttrs = array_map(fn($a)=>[$a->getName(), $a->getArguments()], $meth->getAttributes());
    echo "method attributes: ".json_encode($mAttrs)."\n";

    echo "\n-- feature tour complete --\n";
}
