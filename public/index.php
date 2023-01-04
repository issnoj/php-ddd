<?php

use App\Application\UseCase\BorrowBookUseCase;
use App\Application\UseCase\ChangePlanUseCase;
use App\Application\UseCase\ReturnBookUseCase;
use App\Domain\ValueObject\Plan;
use App\Infrastructure\Query\GetBorrowingBookListQuery;
use App\Infrastructure\Repository\BookRepository;
use App\Infrastructure\Repository\UserRepository;

spl_autoload_register(function ($class_name) {
    include __DIR__ . '/../' . strtr($class_name, ['\\' => DIRECTORY_SEPARATOR]) . '.php';
});

// 事前準備
$userRepository = new UserRepository();
$bookRepository = new BookRepository();
$userRepository->create('Alice');
$bookRepository->create('AAA');
$bookRepository->create('BBB');
$bookRepository->create('CCC');
$user = $userRepository->getById(1);
$borrowBookUseCase = new BorrowBookUseCase();
$changePlanUseCase = new ChangePlanUseCase();
$returnBookUseCase = new ReturnBookUseCase();
$getBorrowingBookListQuery = new GetBorrowingBookListQuery();

// 1. 本を 2 冊借りる
$borrowBookUseCase->execute($user, 1);
$borrowBookUseCase->execute($user, 2);

// 2. 3 冊目を借りようとするとエラーになる
try {
    $borrowBookUseCase->execute($user, 3);
} catch (Exception $exception) {
    echo $exception->getMessage() . PHP_EOL;
}

// 3. フリープランからスタンダードプランに変更する
$changePlanUseCase->execute($user, Plan::Standard);

// 4. 3 冊目を借りる
$borrowBookUseCase->execute($user, 3);

// 5. スタンダードプランからフリープランに変更しようとするとエラーになる
try {
    $changePlanUseCase->execute($user, Plan::Free);
} catch (Exception $exception) {
    echo $exception->getMessage() . PHP_EOL;
}

// 6. 借りた日付より前の日付で返そうとするとエラーになる
try {
    $returnBookUseCase->execute($user, 1, new DateTime("2023-01-01"));
} catch (Exception $exception) {
    echo $exception->getMessage() . PHP_EOL;
}

// 7. 借りた本を返す
$returnBookUseCase->execute($user, 1, new DateTime());

// 8. スタンダードプランからフリープランに変更する
$changePlanUseCase->execute($user, Plan::Free);

// 9. 借りている本を確認する
echo "借りている本：" . PHP_EOL;
foreach ($getBorrowingBookListQuery->execute(1) as $v) {
    echo "\t{$v['id']},{$v['title']},{$v['borrowDate']}" . PHP_EOL;
}