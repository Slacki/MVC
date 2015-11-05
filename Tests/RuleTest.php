<?php

namespace Tests;

use Framework\Rule;

class RuleTest extends \PHPUnit_Framework_TestCase
{
    public $rules = [];

    public function setUp()
    {
        $this->rules = require(__DIR__ . '/routerRules.php');
    }

    public function testRegexCompile()
    {
        $rule1 = new Rule($this->rules[0]['pattern'], $this->rules[0]['route']);
        $rule2 = new Rule($this->rules[1]['pattern'], $this->rules[1]['route']);
        $rule3 = new Rule($this->rules[2]['pattern'], $this->rules[2]['route']);
        $rule4 = new Rule($this->rules[3]['pattern'], $this->rules[3]['route']);

        $this->assertTrue($rule1->regex != null);
        $this->assertTrue($rule2->regex != null);
        $this->assertTrue($rule3->regex != null);
        $this->assertTrue($rule4->regex != null);
    }

    public function testRulesWithNamedParameters()
    {
        $rule1 = new Rule($this->rules[0]['pattern'], $this->rules[0]['route']);
        $rule2 = new Rule($this->rules[1]['pattern'], $this->rules[1]['route']);
        $rule3 = new Rule($this->rules[2]['pattern'], $this->rules[2]['route']);
        $rule4 = new Rule($this->rules[3]['pattern'], $this->rules[3]['route']);

        $route1 = 'site';
        $route1_fail = '!@#';

        $route2 = 'site/index';
        $route2_fail = 'site';

        $route3 = 'site/index/123';
        $route3_fail = 'site/index/sth';

        $route4 = 'site/index/123/someText/bR4ck3Ts/abcde';
        $route4_fail = 'site/index/123/someText/bR4ck3Ts/1';

        $this->assertEquals(1, preg_match($rule1->regex, $route1));
        $this->assertEquals(1, preg_match($rule2->regex, $route2));
        $this->assertEquals(1, preg_match($rule3->regex, $route3));
        $this->assertEquals(1, preg_match($rule4->regex, $route4));

        $this->assertEquals(0, preg_match($rule1->regex, $route1_fail));
        $this->assertEquals(0, preg_match($rule2->regex, $route2_fail));
        $this->assertEquals(0, preg_match($rule3->regex, $route3_fail));
        $this->assertEquals(0, preg_match($rule4->regex, $route4_fail));
    }

    public function testRulesWithStringParameters()
    {
        $rule1 = new Rule($this->rules[4]['pattern'], $this->rules[4]['route']);
        $rule2 = new Rule($this->rules[5]['pattern'], $this->rules[5]['route']);

        $route1 = 'posts';
        $route1_fail = 'post/asd';
        $route1_fail2 = '!@#/!@#';

        $route2 = 'post/123';
        $route2_fail = 'post/asd';
        $route2_fail2 = '!@#/123';

        $this->assertEquals(1, preg_match($rule1->regex, $route1));
        $this->assertEquals(1, preg_match($rule2->regex, $route2));

        $this->assertEquals(0, preg_match($rule1->regex, $route1_fail));
        $this->assertEquals(0, preg_match($rule1->regex, $route1_fail2));
        $this->assertEquals(0, preg_match($rule1->regex, $route2_fail));
        $this->assertEquals(0, preg_match($rule1->regex, $route2_fail2));
    }
}
