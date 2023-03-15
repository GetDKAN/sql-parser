<?php

namespace SqlParserTest;

use PHPUnit\Framework\TestCase;
use SqlParser\SqlParser;

/**
 * SqlParserTest class.
 */
class SqlParserTest extends TestCase
{

    /**
     * Data provider.
     */
    public function dataTestSqlParser()
    {
        return [
            ['foo', false],
            ['[SELECT * FROM abc];', true],
            ['[SELECT *a FROM abc];', false],
            ['[SELECT abc FROM abc];', true],
            ['[SELECT abc,def FROM abc];', true],
            ['[SELECT abc, def FROM abc];', false],
            ["[SELECT * FROM abc][WHERE def = \"hij\"];", true],
            ["[SELECT * FROM abc][ORDER BY qrs ASC];", true],
            ["[SELECT * FROM abc][ORDER BY qrs,tuv DESC][LIMIT 1 OFFSET 2];", true],
            ["[SELECT * FROM abc][LIMIT 1 OFFSET 2];", true],
            ["[SELECT * FROM abc][WHERE def = \"hij\" AND klm = \"nop\"];", true],
            [
                "[SELECT * FROM abc][WHERE def = \"hij\" AND klm = \"nop\"][ORDER BY qrs ASC];",
                true,
            ],
            [
                "[SELECT * FROM abc][WHERE def = \"hij\" AND klm = \"nop\"][ORDER BY qrs, tuv];",
                false,
            ],
            [
                "[SELECT * FROM abc][WHERE def = \"hij\" AND klm = \"nop\"][ORDER BY qrs,tuv DESC];",
                true,
            ],
            [
                "[SELECT * FROM abc][WHERE def = \"hij\" AND klm = \"nop\"][ORDER BY qrs,tuv][LIMIT 1];",
                false,
            ],
            [
                "[SELECT * FROM abc][WHERE def = \"hij\" AND klm = \"nop\"][ORDER BY qrs,tuv ASC][LIMIT 1 OFFSET 2];",
                true,
            ],
            [
                "[SELECT * FROM abc][WHERE def = \"hij\" AND klm = \"nop\"][ORDER BY qrs,tuv ASC][LIMIT 1 OFFSET 2];",
                true,
            ],
            [
                "[SELECT * FROM abc][WHERE def = \"hij\" AND klm = \"nop\"][ORDER BY qrs,tuv DESC][LIMIT 1 OFFSET 2];",
                true,
            ],
        ];
    }

    /**
     * Tests validate and everything else.
     *
     * @param string $sqlString
     *   SQL string.
     * @param bool $expected
     *   Whether the SQL string is valid or not.
     *
     * @dataProvider dataTestSqlParser
     */
    public function testSqlParser($sqlString, $expected)
    {
        $parser = new SqlParser();

        $actual = $parser->validate($sqlString);

        $this->assertEquals($actual, $expected);
    }
}
