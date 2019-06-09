<?php

$vendor_path = 'vendor-bin/box/vendor/humbug/php-scoper/src/PhpParser/NodeVisitor/ClassAliasStmtAppender.php';

if (!file_exists($vendor_path)) {
    die('Vendor file does not exist' . PHP_EOL);
}

$search = '/* @var FullyQualified $originalName */

        $stmts[] = $this->createAliasStmt($originalName, $stmt);';

$replace = '/* @var FullyQualified $originalName */
        $aliasStmt = $this->createAliasStmt($originalName, $stmt);

        $stmts[] = new If_(
            new FuncCall(
                new FullyQualified(\'class_exists\'),
                [
                    new Node\Arg(
                        new Node\Expr\ClassConstFetch(
                            $originalName,
                            \'class\'
                        )
                    )
                ]
            ),
            [\'stmts\' => [$aliasStmt]]
        );';

$contents = file_get_contents($vendor_path);

$contents = str_replace($search, $replace, $contents);

file_put_contents($vendor_path, $contents);