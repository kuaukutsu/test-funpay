# Тестовое задание

Написать функцию формирования sql-запросов (MySQL) из шаблона и значений параметров.

## Проверка

```shell
make app-tests
```

### Static analysis

The code is statically analyzed with [Psalm](https://psalm.dev/). To run static analysis:

```shell
make psalm
```

## Описание

Места вставки значений в шаблон помечаются вопросительным знаком, после которого может следовать спецификатор преобразования.
Спецификаторы:
?d - конвертация в целое число
?f - конвертация в число с плавающей точкой
?a - массив значений
?# - идентификатор или массив идентификаторов

Если спецификатор не указан, то используется тип переданного значения, но допускаются только типы string, int, float, bool (приводится к 0 или 1) и null.
Параметры ?, ?d, ?f могут принимать значения null (в этом случае в шаблон вставляется NULL).
Строки и идентификаторы автоматически экранируются.

Массив (параметр ?a) преобразуется либо в список значений через запятую (список), либо в пары идентификатор и значение через запятую (ассоциативный массив).
Каждое значение из массива форматируется в зависимости от его типа (идентично универсальному параметру без спецификатора).

Также необходимо реализовать условные блоки, помечаемые фигурными скобками.
Если внутри условного блока есть хотя бы один параметр со специальным значением, то блок не попадает в сформированный запрос.
Специальное значение должно возвращаться методом skip. Нужно выбрать подходящее значение на своё усмотрение.
Условные блоки не могут быть вложенными.

При ошибках в шаблонах или значениях выбрасывать исключения.

Для упрощения задачи предполагается, что в передаваемом шаблоне не будут использоваться комментарии sql.

В файле Database.php находится заготовка класса с заглушками в виде исключений. Нужно реализовать методы buildQuery и skip.
В файле DatabaseTest.php находятся примеры (тесты). Тесты обязательно должны быть успешными (в противном случае код рассматриваться не будет).

Код должен работать с php 8.3.
