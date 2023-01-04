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
$book1 = $bookRepository->getById(1);
$book2 = $bookRepository->getById(2);
$book3 = $bookRepository->getById(3);

// 1. 本を 2 冊借りる
$borrowBookUseCase = new BorrowBookUseCase();
$borrowBookUseCase->execute($user, $book1);
$borrowBookUseCase->execute($user, $book2);

// 2. 3 冊目を借りようとするとエラーになる
try {
    $borrowBookUseCase->execute($user, $book3);
} catch (Exception $exception) {
    echo $exception->getMessage() . PHP_EOL;
}

// 3. フリープランからスタンダードプランに変更する
$changePlanUseCase = new ChangePlanUseCase();
$changePlanUseCase->execute($user, Plan::Standard);

// 4. 3 冊目を借りる
$borrowBookUseCase->execute($user, $book3);

// 5. スタンダードプランからフリープランに変更しようとするとエラーになる
try {
    $changePlanUseCase->execute($user, Plan::Free);
} catch (Exception $exception) {
    echo $exception->getMessage() . PHP_EOL;
}

// 6. 最初に借りた本を返す
$returnBookUseCase = new ReturnBookUseCase();
$returnBookUseCase->execute($user, $book1);

// 7. スタンダードプランからフリープランに変更する
$changePlanUseCase->execute($user, Plan::Free);

// 8. 借りている本を確認する
$getBorrowingBookListQuery = new GetBorrowingBookListQuery();
echo "借りている本：" . PHP_EOL;
foreach ($getBorrowingBookListQuery->execute(1) as $v) {
    echo "\t{$v['id']},{$v['title']},{$v['borrowDate']}" . PHP_EOL;
}