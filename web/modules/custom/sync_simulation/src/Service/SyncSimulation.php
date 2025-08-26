<?php

namespace Drupal\sync_simulation\Service;

/**
 * Сервис симуляции синхронизации сотрудников.
 */
class SyncSimulation {

  /**
   * Возвращает список из 20 сотрудников.
   *
   * Каждый сотрудник представлен ассоциативным массивом с ключами:
   * - "FIO": строка ровно 60 символов (UTF-8), при необходимости обрезается/паддится пробелами
   * - "birth_date": строка в формате YYYY-MM-DD (ГГГГ-ММ-ДД), дата в диапазоне 1980-01-01 — 1989-12-31
   * - "enter_date": строка в формате YYYY-MM-DD (ГГГГ-ММ-ДД), дата в диапазоне 2010-01-01 — 2019-12-31
   * - "fired_date": строка в формате YYYY-MM-DD (ГГГГ-ММ-ДД), дата в диапазоне 2018-01-01 — 2023-12-31
   * - "fiz_code": уникальная строка [A-Za-z0-9] длиной 10 символов
   * - "unit": строка "01" или "02" (значения сформированы и перемешаны случайным образом)
   * - "position": строка "Руководитель"/"Заместитель руководителя"/"Ведущий инженер"
   * - "position_type": строка "01"/"02"/"03"
   * - "phone": строка телефона в формате +7XXXXXXXXXX, уникальная на запись
   * - "inner_phone": строка из 4 цифр (внутренний номер), уникальная на запись
   * - "email": строка e-mail, уникальная на запись
   * - "address": строка адреса; для unit "01" — адрес A, для unit "02" — адрес B
   * - "number": целое число, начиная с 300 и увеличивающееся на 1 у каждого следующего
   *
   * @return array<int, array{FIO:string, birth_date:string, enter_date:string, fired_date:string, 
   *     fiz_code:string, unit:string, position:string, position_type:string, phone:string, inner_phone:string, email:string, 
   *     address:string, number:int}>
   */
  public function getEmployees(): array {
    $fio = [
      'Иванов-Петров Иван Иванович',
      'Петров Пётр Петрович',
      'Сидорова-Кузнецова Анна Сергеевна',
      'Кузнецов Александр Владимирович',
      'Соколова Мария Андреевна',
      'Морозов Дмитрий Алексеевич',
      'Егорова Екатерина Олеговна',
      'Волков Никита Викторович',
      'Фёдоров Кирилл Сергеевич',
      'Павлова Дарья Михайловна',
      'Николаев Артём Евгеньевич',
      'Борисова Татьяна Викторовна',
      'Григорьев Максим Игоревич',
      'Алексеева Ольга Константиновна',
      'Ковалёв Сергей Дмитриевич',
      'Зайцев Роман Павлович',
      'Киселева Наталья Ивановна',
      'Орлов Денис Сергеевич',
      'Макарова Ирина Владимировна',
      'Трофимов Алексей Петрович',
    ];

    // 20 детерминированных дат в интервале 1980-01-01 .. 1989-12-31 (YYYY-MM-DD).
    $birth_date = [
      '1980-01-05',
      '1981-03-12',
      '1982-07-24',
      '1983-11-15',
      '1984-02-02',
      '1985-05-19',
      '1986-08-30',
      '1987-10-07',
      '1988-04-21',
      '1989-12-31',
      '1980-06-14',
      '1981-09-03',
      '1982-12-11',
      '1983-04-09',
      '1984-07-28',
      '1980-02-20',
      '1981-07-08',
      '1986-01-27',
      '1988-09-14',
      '1989-05-05',
    ];

    $enter_date = [
      '2010-01-05',
      '2011-03-12',
      '2012-07-24',
      '2013-11-15',
      '2014-02-02',
      '2015-05-19',
      '2016-08-30',
      '2017-10-07',
      '2018-04-21',
      '2019-12-31',
      '2010-06-14',
      '2011-09-03',
      '2012-12-11',
      '2013-04-09',
      '2014-07-28',
      '2010-08-15',
      '2012-02-28',
      '2016-11-03',
      '2018-07-19',
      '2019-01-22',
    ];

    $fired_date = [
      '',
      '2018-03-12',
      '',
      '',
      '',
      '',
      '',
      '',
      '',
      '',
      '',
      '',
      '',
      '2023-04-09',
      '',
      '',
      '',
      '',
      '2021-10-05',
      '',
    ];

    $code = [
      'AdVUAlmVdy',
      'jyxDsKrUFM',
      'oA80a12fi9',
      '3vaj94Dh1E',
      '0frZxKU5kQ',
      'ibOdlwSeXI',
      'pa4ixLPZoB',
      'P0Z1JyjcBC',
      'TuFcUnUu5i',
      'vl7WaqD1D3',
      'zajhQxB6gV',
      'FITPlEpy3a',
      'PVgFuCWFzA',
      'gnzfrmZTop',
      'sTA8a9BrfG',
      'eB3kL9aQ2M',
      'Hn7yP0sXcV',
      'Qw2Er9TtY1',
      'mN8bVc4KzR',
      'J5uL0pSxDa',
    ];

    // Массив из 20 значений '01'/'02'
    $unit = [
      "01",
      "02",
      "02",
      "02",
      "01",
      "01",
      "02",
      "01",
      "01",
      "01",
      "01",
      "02",
      "02",
      "01",
      "02",
      "01",
      "02",
      "01",
      "02",
      "01",
    ];

    // Адреса для соответствия unit: для "01" и для "02" разные значения
    $address = [
      "г. Москва, ул. Тверская, д. 1",
      "г. Санкт-Петербург, Невский пр., д. 10",
      "г. Санкт-Петербург, Невский пр., д. 10",
      "г. Санкт-Петербург, Невский пр., д. 10",
      "г. Москва, ул. Тверская, д. 1",
      "г. Москва, ул. Тверская, д. 1",
      "г. Санкт-Петербург, Невский пр., д. 10",
      "г. Москва, ул. Тверская, д. 1",
      "г. Москва, ул. Тверская, д. 1",
      "г. Москва, ул. Тверская, д. 1",
      "г. Москва, ул. Тверская, д. 1",
      "г. Санкт-Петербург, Невский пр., д. 10",
      "г. Санкт-Петербург, Невский пр., д. 10",
      "г. Москва, ул. Тверская, д. 1",
      "г. Санкт-Петербург, Невский пр., д. 10",
      "г. Москва, ул. Тверская, д. 1",
      "г. Санкт-Петербург, Невский пр., д. 10",
      "г. Москва, ул. Тверская, д. 1",
      "г. Санкт-Петербург, Невский пр., д. 10",
      "г. Москва, ул. Тверская, д. 1",
    ];

    $position = [
      "Руководитель",
      "Заместитель руководителя",
      "Ведущий инженер",
      "Заместитель руководителя",
      "Ведущий инженер",
      "Ведущий инженер",
      "Ведущий инженер",
      "Ведущий инженер",
      "Ведущий инженер",
      "Заместитель руководителя",
      "Заместитель руководителя",
      "Ведущий инженер",
      "Руководитель",
      "Ведущий инженер",
      "Ведущий инженер",
      "Ведущий инженер",
      "Ведущий инженер",
      "Ведущий инженер",
      "Ведущий инженер",
      "Ведущий инженер",
    ];

    $position_type = [
      "01",
      "02",
      "03",
      "02",
      "03",
      "03",
      "03",
      "03",
      "03",
      "02",
      "02",
      "03",
      "01",
      "03",
      "03",
      "03",
      "03",
      "03",
      "03",
      "03",
    ];

    // 20 уникальных телефонов в формате +7XXXXXXXXXX
    $phone = [
      '+79001230001',
      '+79001230002',
      '+79001230003',
      '+79001230004',
      '+79001230005',
      '+79001230006',
      '+79001230007',
      '+79001230008',
      '+79001230009',
      '+79001230010',
      '+79001230011',
      '+79001230012',
      '+79001230013',
      '+79001230014',
      '+79001230015',
      '+79001230016',
      '+79001230017',
      '+79001230018',
      '+79001230019',
      '+79001230020',
    ];

    // 20 уникальных e-mail адресов
    $email = [
      'user300@example.com',
      'user301@example.com',
      'user302@example.com',
      'user303@example.com',
      'user304@example.com',
      'user305@example.com',
      'user306@example.com',
      'user307@example.com',
      'user308@example.com',
      'user309@example.com',
      'user310@example.com',
      'user311@example.com',
      'user312@example.com',
      'user313@example.com',
      'user314@example.com',
      'user315@example.com',
      'user316@example.com',
      'user317@example.com',
      'user318@example.com',
      'user319@example.com',
    ];

    // 20 уникальных 4-значных внутренних номеров
    $inner_phone = [
      '10-01', '44-02', '74-03', '18-04', '91-05',
      '10-06', '44-07', '74-08', '18-09', '91-10',
      '10-11', '44-12', '74-13', '18-14', '91-15',
      '10-16', '44-17', '74-18', '18-19', '91-20',
    ];

    $result = [];
    for ($i = 0; $i < 20; $i++) {
      $result[] = [
        'FIO' => $fio[$i],
        'birth_date' => $birth_date[$i],
        'enter_date' => $enter_date[$i],
        'fired_date' => $fired_date[$i],
        'fiz_code' => $code[$i],
        'unit' => $unit[$i],
        'position' => $position[$i],
        'position_type' => $position_type[$i],
        'phone' => $phone[$i],
        'inner_phone' => $inner_phone[$i],
        'email' => $email[$i],
        'address' => $address[$i],
        'number' => 300 + $i,
      ];
    }

    return $result;
  }

  /**
   * Возвращает список родственников (семью) для демонстрации.
   *
   * Каждая запись описывает одного родственника сотрудника и содержит:
   * - fio: ФИО
   * - relation: степень родства ("муж"|"жена"|"сын"|"дочь")
   * - relation_code: код степени родства (муж=1, жена=2, сын=3, дочь=4)
   * - birth_date: дата рождения в формате YYYY-MM-DD
   * - fiz_code: код связанного физлица
   *
   * @return array<int, array{
   *   fio: string,
   *   relation: string,
   *   relation_code: int,
   *   birth_date: string,
   *   fiz_code: string,
   * }>
   */
  public function getFamily(): array {
    $result = [
      [
        'fio' => "Иванов-Петрова Дарья Сергеевна",
        'relation' => "жена",
        'relation_code' => 2,
        'birth_date' => '1981-01-05',
        'fiz_code' => 'AdVUAlmVdy'
      ],
      [
        'fio' => "Иванов-Петров Сергей Александрович",
        'relation' => "сын",
        'relation_code' => 3,
        'birth_date' => '2013-02-14',
        'fiz_code' => 'AdVUAlmVdy'
      ],
      [
        'fio' => "Иванов-Петров Кирилл Александрович",
        'relation' => "сын",
        'relation_code' => 3,
        'birth_date' => '2006-03-03',
        'fiz_code' => 'AdVUAlmVdy'
      ],
      [
        'fio' => "Петрова Дарья Александровна" ,
        'relation' => "жена",
        'relation_code' => 2,
        'birth_date' => '1983-04-21',
        'fiz_code' => 'jyxDsKrUFM',
      ],
      [
        'fio' => "Петров Кирилл Александрович" ,
        'relation' => "сын",
        'relation_code' => 3,
        'birth_date' => '2011-05-12',
        'fiz_code' => 'jyxDsKrUFM',
      ],
      [
        'fio' => "Сидорова-Кузнецов Андрей Александрович",
        'relation' => "муж",
        'relation_code' => 1,
        'birth_date' => '1984-06-30',
        'fiz_code' => 'oA80a12fi9',
      ],
      [
        'fio' => "Сидорова-Кузнецова Татьяна Андреевна",
        'relation' => "дочь",
        'relation_code' => 4,
        'birth_date' => '2008-07-07',
        'fiz_code' => 'oA80a12fi9',
      ],
      [
        'fio' => "Кузнецова Татьяна Михайловна",
        'relation' => "жена",
        'relation_code' => 2,
        'birth_date' => '1983-08-19',
        'fiz_code' => '3vaj94Dh1E',
      ],
      [
        'fio' => "Кузнецова Татьяна Владимировна",
        'relation' => "дочь",
        'relation_code' => 4,
        'birth_date' => '2017-09-09',
        'fiz_code' => '3vaj94Dh1E',
      ],
      [
        'fio' => "Соколов Артём Андреевич" ,
        'relation' => "муж",
        'relation_code' => 1,
        'birth_date' => '1983-10-31',
        'fiz_code' => '0frZxKU5kQ',
      ],
      [
        'fio' => "Соколов Александр Дмитриевич",
        'relation' => "сын",
        'relation_code' => 3,
        'birth_date' => '2024-11-11',
        'fiz_code' => '0frZxKU5kQ',
      ],
      [
        'fio' => "Соколов Никита Андреевич",
        'relation' => "сын",
        'relation_code' => 3,
        'birth_date' => '2009-12-24',
        'fiz_code' => '0frZxKU5kQ',
      ],
      [
        'fio' => "Соколова Полина Александровна" ,
        'relation' => "дочь",
        'relation_code' => 4,
        'birth_date' => '2018-01-17',
        'fiz_code' => '0frZxKU5kQ',
      ],
      [
        'fio' => "Морозова Татьяна Дмитриевна" ,
        'relation' => "жена",
        'relation_code' => 2,
        'birth_date' => '1986-02-28',
        'fiz_code' => 'ibOdlwSeXI',
      ],
      [
        'fio' => "Морозова Мария Михайловна" ,
        'relation' => "дочь",
        'relation_code' => 4,
        'birth_date' => '2006-03-15',
        'fiz_code' => 'ibOdlwSeXI',
      ],
      [
        'fio' => "Морозов Дмитрий Александрович" ,
        'relation' => "сын",
        'relation_code' => 3,
        'birth_date' => '2007-04-09',
        'fiz_code' => 'ibOdlwSeXI',
      ],
      [
        'fio' => "Егоров Дмитрий Михайлович" ,
        'relation' => "муж",
        'relation_code' => 1,
        'birth_date' => '1988-05-27',
        'fiz_code' => 'pa4ixLPZoB',
      ],
      [
        'fio' => "Егоров Сергей Андреевич" ,
        'relation' => "сын",
        'relation_code' => 3,
        'birth_date' => '2018-06-18',
        'fiz_code' => 'pa4ixLPZoB',
      ],
      [
        'fio' => "Егорова Анна Романовна",
        'relation' => "дочь",
        'relation_code' => 4,
        'birth_date' => '2011-07-29',
        'fiz_code' => 'pa4ixLPZoB',
      ],
      [
        'fio' => "Егоров Дмитрий Сергеевич",
        'relation' => "сын",
        'relation_code' => 3,
        'birth_date' => '2006-08-02',
        'fiz_code' => 'pa4ixLPZoB',
      ],
      [
        'fio' => "Волков Андрей Андреевич" ,
        'relation' => "муж",
        'relation_code' => 1,
        'birth_date' => '1986-09-23',
        'fiz_code' => 'P0Z1JyjcBC',
      ],
      [
        'fio' => "Волкова Екатерина Михайловна",
        'relation' => "дочь",
        'relation_code' => 4,
        'birth_date' => '2022-10-05',
        'fiz_code' => 'P0Z1JyjcBC',
      ],
      [
        'fio' => "Фёдорова Наталья Михайловна" ,
        'relation' => "жена",
        'relation_code' => 2,
        'birth_date' => '1989-11-26',
        'fiz_code' => 'TuFcUnUu5i',
      ],
      [
        'fio' => "Фёдоров Дмитрий Сергеевич" ,
        'relation' => "сын",
        'relation_code' => 3,
        'birth_date' => '2023-12-06',
        'fiz_code' => 'TuFcUnUu5i',
      ],
      [
        'fio' => 'Фёдоров Владимир Романович',
        'relation' => "сын",
        'relation_code' => 3,
        'birth_date' => '2009-01-22',
        'fiz_code' => 'TuFcUnUu5i',
      ],
      [
        'fio' => "Фёдоров Кирилл Александрович",
        'relation' => "сын",
        'relation_code' => 3,
        'birth_date' => '2019-02-07',
        'fiz_code' => 'TuFcUnUu5i',
      ],
      [
        'fio' => "Павлов Роман Дмитриевич" ,
        'relation' => "муж",
        'relation_code' => 1,
        'birth_date' => '1991-03-26',
        'fiz_code' => 'vl7WaqD1D3',
      ],
      [
        'fio' => "Павлова Екатерина Дмитриевна",
        'relation' => "дочь",
        'relation_code' => 4,
        'birth_date' => '2016-04-14',
        'fiz_code' => 'vl7WaqD1D3',
      ],
      [
        'fio' => "Николаева Дарья Андреевна" ,
        'relation' => "жена",
        'relation_code' => 2,
        'birth_date' => '1982-05-08',
        'fiz_code' => 'zajhQxB6gV',
      ],
      [
        'fio' => "Николаев Владимир Андреевич" ,
        'relation' => "сын",
        'relation_code' => 3,
        'birth_date' => '2007-06-22',
        'fiz_code' => 'zajhQxB6gV',
      ],
      [
        'fio' => "Николаев Дмитрий Михайлович" ,
        'relation' => "сын",
        'relation_code' => 3,
        'birth_date' => '2013-07-13',
        'fiz_code' => 'zajhQxB6gV',
      ],
      [
        'fio' => "Борисов Никита Владимирович" ,
        'relation' => "муж",
        'relation_code' => 1,
        'birth_date' => '1984-08-27',
        'fiz_code' => 'FITPlEpy3a',
      ],
      [
        'fio' => "Борисов Михаил Андреевич",
        'relation' => "сын",
        'relation_code' => 3,
        'birth_date' => '2015-09-14',
        'fiz_code' => 'FITPlEpy3a',
      ],
      [
        'fio' => "Борисов Никита Александрович",
        'relation' => "сын",
        'relation_code' => 3,
        'birth_date' => '2022-10-18',
        'fiz_code' => 'FITPlEpy3a',
      ],
      [
        'fio' => "Борисова Ирина Дмитриевна" ,
        'relation' => "дочь",
        'relation_code' => 4,
        'birth_date' => '2020-11-05',
        'fiz_code' => 'FITPlEpy3a',
      ],
      [
        'fio' => "Григорьева Екатерина Михайловна" ,
        'relation' => "жена",
        'relation_code' => 2,
        'birth_date' => '1981-12-12',
        'fiz_code' => 'PVgFuCWFzA',
      ],
      [
        'fio' => "Григорьева Дарья Михайловна" ,
        'relation' => "дочь",
        'relation_code' => 4,
        'birth_date' => '2021-01-29',
        'fiz_code' => 'PVgFuCWFzA',
      ],
      [
        'fio' => "Григорьев Артём Дмитриевич",
        'relation' => "сын",
        'relation_code' => 3,
        'birth_date' => '2010-02-19',
        'fiz_code' => 'PVgFuCWFzA',
      ],
      [
        'fio' => "Алексеев Дмитрий Михайлович" ,
        'relation' => "муж",
        'relation_code' => 1,
        'birth_date' => '1982-03-08',
        'fiz_code' => 'gnzfrmZTop',
      ],
      [
        'fio' => "Алексеева Мария Владимировна",
        'relation' => "дочь",
        'relation_code' => 4,
        'birth_date' => '2006-04-25',
        'fiz_code' => 'gnzfrmZTop',
      ],
      [
        'fio' => "Алексеев Александр Сергеевич",
        'relation' => "сын",
        'relation_code' => 3,
        'birth_date' => '2005-05-31',
        'fiz_code' => 'gnzfrmZTop',
      ],
      [
        'fio' => "Ковалёв Ольга Владимировна",
        'relation' => "жена",
        'relation_code' => 2,
        'birth_date' => '1983-06-11',
        'fiz_code' => 'sTA8a9BrfG',
      ],
      [
        'fio' => "Ковалёв Екатерина Романовна" ,
        'relation' => "дочь",
        'relation_code' => 4,
        'birth_date' => '2011-07-21',
        'fiz_code' => 'sTA8a9BrfG',
      ],
      [
        'fio' => "Ковалёв Александр Владимирович",
        'relation' => "сын",
        'relation_code' => 3,
        'birth_date' => '2013-08-10',
        'fiz_code' => 'sTA8a9BrfG',
      ],
      [
        'fio' => "Ковалёв Артём Сергеевич" ,
        'relation' => "сын",
        'relation_code' => 3,
        'birth_date' => '2014-09-30',
        'fiz_code' => 'sTA8a9BrfG',
      ],
      [
        'fio' => "Зайцева Полина Александровна",
        'relation' => "жена",
        'relation_code' => 2,
        'birth_date' => '1979-10-12',
        'fiz_code' => 'eB3kL9aQ2M',
      ],
      [
        'fio' => "Зайцев Никита Романович" ,
        'relation' => "сын",
        'relation_code' => 3,
        'birth_date' => '2015-11-20',
        'fiz_code' => 'eB3kL9aQ2M',
      ],
      [
        'fio' => "Зайцев Артём Сергеевич",
        'relation' => "сын",
        'relation_code' => 3,
        'birth_date' => '2019-12-03',
        'fiz_code' => 'eB3kL9aQ2M',
      ],
      [
        'fio' => "Киселев Кирилл Романович",
        'relation' => "муж",
        'relation_code' => 1,
        'birth_date' => '1983-01-10',
        'fiz_code' => 'Hn7yP0sXcV',
      ],
      [
        'fio' => "Киселева Анна Сергеевна" ,
        'relation' => "дочь",
        'relation_code' => 4,
        'birth_date' => '2005-02-23',
        'fiz_code' => 'Hn7yP0sXcV',
      ],
      [
        'fio' => "Киселев Александр Михайлович",
        'relation' => "сын",
        'relation_code' => 3,
        'birth_date' => '2013-03-05',
        'fiz_code' => 'Hn7yP0sXcV',
      ],
      [
        'fio' => "Орлова Наталья Сергеевна",
        'relation' => "жена",
        'relation_code' => 2,
        'birth_date' => '1985-04-30',
        'fiz_code' => 'Qw2Er9TtY1',
      ],
      [
        'fio' => "Орлова Татьяна Александровна",
        'relation' => "дочь",
        'relation_code' => 4,
        'birth_date' => '2020-05-19',
        'fiz_code' => 'Qw2Er9TtY1',
      ],
      [
        'fio' => "Макаров Роман Михайлович",
        'relation' => "муж",
        'relation_code' => 1,
        'birth_date' => '1988-06-07',
        'fiz_code' => 'mN8bVc4KzR',
      ],
      [
        'fio' => "Макарова Дарья Дмитриевна" ,
        'relation' => "дочь",
        'relation_code' => 4,
        'birth_date' => '2010-07-25',
        'fiz_code' => 'mN8bVc4KzR',
      ],
      [
        'fio' => "Макаров Никита Сергеевич",
        'relation' => "сын",
        'relation_code' => 3,
        'birth_date' => '2017-08-15',
        'fiz_code' => 'mN8bVc4KzR',
      ],
      [
        'fio' => "Макаров Михаил Сергеевич",
        'relation' => "сын",
        'relation_code' => 3,
        'birth_date' => '2009-09-05',
        'fiz_code' => 'mN8bVc4KzR',
      ],
      [
        'fio' => "Трофимова Наталья Дмитриевна",
        'relation' => "жена",
        'relation_code' => 2,
        'birth_date' => '1987-10-22',
        'fiz_code' => 'J5uL0pSxDa',
      ],
      [
        'fio' => "Трофимов Кирилл Владимирович",
        'relation' => "сын",
        'relation_code' => 3,
        'birth_date' => '2007-11-02',
        'fiz_code' => 'J5uL0pSxDa',
      ],
      [
        'fio' => "Трофимов Александр Сергеевич",
        'relation' => "сын",
        'relation_code' => 3,
        'birth_date' => '2021-12-27',
        'fiz_code' => 'J5uL0pSxDa',
      ],
      [
        'fio' => "Трофимов Роман Александрович",
        'relation' => "сын",
        'relation_code' => 3,
        'birth_date' => '2023-01-03',
        'fiz_code' => 'J5uL0pSxDa',
      ],
    ];
    return $result;
  }

  /**
   * Возвращает список образований (статический массив), как демонстрационные данные.
   *
   * Каждая запись описывает одно образование сотрудника и содержит:
   * - education_type: тип образования ("высшее"|"среднее специальное")
   * - specialty: специальность
   * - institution: наименование учебного заведения (РБ/РФ)
   * - start_date: дата начала обучения (YYYY-MM-DD)
   * - end_date: дата окончания обучения (YYYY-MM-DD)
   * - fiz_code: код связанного физлица
   *
   * @return array<int, array{
   *   education_type: string,
   *   specialty: string,
   *   institution: string,
   *   start_date: string,
   *   end_date: string,
   *   fiz_code: string,
   * }>
   */
  public function getEducation(): array {
    $result = [
      [
        'education_type' => 'высшее',
        'specialty' => 'Программная инженерия',
        'institution' => 'БГУИР (Минск)',
        'start_date' => '1998-09-01',
        'end_date' => '2003-06-30',
        'fiz_code' => 'AdVUAlmVdy',
      ],
      [
        'education_type' => 'среднее специальное',
        'specialty' => 'Информационные системы и технологии',
        'institution' => 'Минский радиотехнический колледж',
        'start_date' => '1995-09-01',
        'end_date' => '1998-06-30',
        'fiz_code' => 'AdVUAlmVdy',
      ],
      [
        'education_type' => 'высшее',
        'specialty' => 'Экономика',
        'institution' => 'БГЭУ (Минск)',
        'start_date' => '1999-09-01',
        'end_date' => '2004-06-30',
        'fiz_code' => 'jyxDsKrUFM',
      ],
      [
        'education_type' => 'высшее',
        'specialty' => 'Юриспруденция',
        'institution' => 'СПбГУ',
        'start_date' => '2001-09-01',
        'end_date' => '2006-06-30',
        'fiz_code' => 'oA80a12fi9',
      ],
      [
        'education_type' => 'среднее специальное',
        'specialty' => 'Бухгалтерский учет и аудит',
        'institution' => 'Санкт-Петербургский колледж экономики',
        'start_date' => '1998-09-01',
        'end_date' => '2001-06-30',
        'fiz_code' => 'oA80a12fi9',
      ],
      [
        'education_type' => 'высшее',
        'specialty' => 'Электроэнергетика и электротехника',
        'institution' => 'МГТУ им. Н.Э. Баумана',
        'start_date' => '2000-09-01',
        'end_date' => '2005-06-30',
        'fiz_code' => '3vaj94Dh1E',
      ],
      [
        'education_type' => 'высшее',
        'specialty' => 'Стандартизация и метрология',
        'institution' => 'ИТМО',
        'start_date' => '2006-09-01',
        'end_date' => '2008-06-30',
        'fiz_code' => '3vaj94Dh1E',
      ],
      [
        'education_type' => 'среднее специальное',
        'specialty' => 'Радиотехника',
        'institution' => 'Московский колледж связи №54',
        'start_date' => '1997-09-01',
        'end_date' => '2000-06-30',
        'fiz_code' => '3vaj94Dh1E',
      ],
      [
        'education_type' => 'высшее',
        'specialty' => 'Информационные системы и технологии',
        'institution' => 'НИУ ВШЭ',
        'start_date' => '2002-09-01',
        'end_date' => '2006-06-30',
        'fiz_code' => '0frZxKU5kQ',
      ],
      [
        'education_type' => 'среднее специальное',
        'specialty' => 'Логистика',
        'institution' => 'Московский колледж управления и права',
        'start_date' => '1999-09-01',
        'end_date' => '2002-06-30',
        'fiz_code' => '0frZxKU5kQ',
      ],
      [
        'education_type' => 'высшее',
        'specialty' => 'Промышленное и гражданское строительство',
        'institution' => 'БНТУ (Минск)',
        'start_date' => '1997-09-01',
        'end_date' => '2002-06-30',
        'fiz_code' => 'ibOdlwSeXI',
      ],
      [
        'education_type' => 'высшее',
        'specialty' => 'Менеджмент',
        'institution' => 'РАНХиГС',
        'start_date' => '2003-09-01',
        'end_date' => '2007-06-30',
        'fiz_code' => 'pa4ixLPZoB',
      ],
      [
        'education_type' => 'среднее специальное',
        'specialty' => 'Маркетинг',
        'institution' => 'Колледж бизнеса и технологий (Минск)',
        'start_date' => '2000-09-01',
        'end_date' => '2003-06-30',
        'fiz_code' => 'pa4ixLPZoB',
      ],
      [
        'education_type' => 'высшее',
        'specialty' => 'Математика',
        'institution' => 'МГУ им. М.В. Ломоносова',
        'start_date' => '1996-09-01',
        'end_date' => '2001-06-30',
        'fiz_code' => 'P0Z1JyjcBC',
      ],
      [
        'education_type' => 'высшее',
        'specialty' => 'Государственное и муниципальное управление',
        'institution' => 'БГУ (Минск)',
        'start_date' => '1998-09-01',
        'end_date' => '2003-06-30',
        'fiz_code' => 'TuFcUnUu5i',
      ],
      [
        'education_type' => 'среднее специальное',
        'specialty' => 'Финансы и кредит',
        'institution' => 'Минский финансово-экономический колледж',
        'start_date' => '1995-09-01',
        'end_date' => '1998-06-30',
        'fiz_code' => 'TuFcUnUu5i',
      ],
      [
        'education_type' => 'высшее',
        'specialty' => 'Прикладная информатика',
        'institution' => 'БГУ (Минск)',
        'start_date' => '2001-09-01',
        'end_date' => '2005-06-30',
        'fiz_code' => 'vl7WaqD1D3',
      ],
      [
        'education_type' => 'высшее',
        'specialty' => 'Информационные системы и технологии',
        'institution' => 'МФТИ',
        'start_date' => '2006-09-01',
        'end_date' => '2008-06-30',
        'fiz_code' => 'vl7WaqD1D3',
      ],
      [
        'education_type' => 'среднее специальное',
        'specialty' => 'Радиотехника',
        'institution' => 'Минский радиотехнический колледж',
        'start_date' => '1998-09-01',
        'end_date' => '2001-06-30',
        'fiz_code' => 'vl7WaqD1D3',
      ],
      [
        'education_type' => 'высшее',
        'specialty' => 'Юриспруденция',
        'institution' => 'БГУ (Минск)',
        'start_date' => '1997-09-01',
        'end_date' => '2002-06-30',
        'fiz_code' => 'zajhQxB6gV',
      ],
      [
        'education_type' => 'высшее',
        'specialty' => 'Экономика',
        'institution' => 'НИУ ВШЭ',
        'start_date' => '1999-09-01',
        'end_date' => '2004-06-30',
        'fiz_code' => 'FITPlEpy3a',
      ],
      [
        'education_type' => 'среднее специальное',
        'specialty' => 'Маркетинг',
        'institution' => 'Смольный институт экономики и финансов (колледж)',
        'start_date' => '1996-09-01',
        'end_date' => '1999-06-30',
        'fiz_code' => 'FITPlEpy3a',
      ],
      [
        'education_type' => 'высшее',
        'specialty' => 'Теплоэнергетика',
        'institution' => 'СПбГУ',
        'start_date' => '2000-09-01',
        'end_date' => '2005-06-30',
        'fiz_code' => 'PVgFuCWFzA',
      ],
      [
        'education_type' => 'среднее специальное',
        'specialty' => 'Электроэнергетика и электротехника',
        'institution' => 'Колледж энергетики (Санкт-Петербург)',
        'start_date' => '1997-09-01',
        'end_date' => '2000-06-30',
        'fiz_code' => 'PVgFuCWFzA',
      ],
      [
        'education_type' => 'высшее',
        'specialty' => 'Математика',
        'institution' => 'БГУ (Минск)',
        'start_date' => '1995-09-01',
        'end_date' => '2000-06-30',
        'fiz_code' => 'gnzfrmZTop',
      ],
      [
        'education_type' => 'высшее',
        'specialty' => 'Радиотехника',
        'institution' => 'МГТУ им. Н.Э. Баумана',
        'start_date' => '1998-09-01',
        'end_date' => '2003-06-30',
        'fiz_code' => 'sTA8a9BrfG',
      ],
      [
        'education_type' => 'среднее специальное',
        'specialty' => 'Информационные системы и технологии',
        'institution' => 'Колледж связи №54 (Москва)',
        'start_date' => '1995-09-01',
        'end_date' => '1998-06-30',
        'fiz_code' => 'sTA8a9BrfG',
      ],
      [
        'education_type' => 'высшее',
        'specialty' => 'Менеджмент',
        'institution' => 'РАНХиГС',
        'start_date' => '1999-09-01',
        'end_date' => '2004-06-30',
        'fiz_code' => 'eB3kL9aQ2M',
      ],
      [
        'education_type' => 'высшее',
        'specialty' => 'Государственное и муниципальное управление',
        'institution' => 'БГЭУ (Минск)',
        'start_date' => '2005-09-01',
        'end_date' => '2007-06-30',
        'fiz_code' => 'eB3kL9aQ2M',
      ],
      [
        'education_type' => 'среднее специальное',
        'specialty' => 'Бухгалтерский учет и аудит',
        'institution' => 'Минский финансово-экономический колледж',
        'start_date' => '1996-09-01',
        'end_date' => '1999-06-30',
        'fiz_code' => 'eB3kL9aQ2M',
      ],
      [
        'education_type' => 'высшее',
        'specialty' => 'Программная инженерия',
        'institution' => 'НИУ ВШЭ',
        'start_date' => '2002-09-01',
        'end_date' => '2006-06-30',
        'fiz_code' => 'Hn7yP0sXcV',
      ],
      [
        'education_type' => 'высшее',
        'specialty' => 'Экономика',
        'institution' => 'БГУ (Минск)',
        'start_date' => '1997-09-01',
        'end_date' => '2002-06-30',
        'fiz_code' => 'Qw2Er9TtY1',
      ],
      [
        'education_type' => 'среднее специальное',
        'specialty' => 'Логистика',
        'institution' => 'Колледж бизнеса и технологий (Минск)',
        'start_date' => '1994-09-01',
        'end_date' => '1997-06-30',
        'fiz_code' => 'Qw2Er9TtY1',
      ],
      [
        'education_type' => 'высшее',
        'specialty' => 'Информационные системы и технологии',
        'institution' => 'МФТИ',
        'start_date' => '1998-09-01',
        'end_date' => '2003-06-30',
        'fiz_code' => 'mN8bVc4KzR',
      ],
      [
        'education_type' => 'среднее специальное',
        'specialty' => 'Маркетинг',
        'institution' => 'Санкт-Петербургский колледж экономики',
        'start_date' => '1995-09-01',
        'end_date' => '1998-06-30',
        'fiz_code' => 'mN8bVc4KzR',
      ],
      [
        'education_type' => 'высшее',
        'specialty' => 'Юриспруденция',
        'institution' => 'СПбГУ',
        'start_date' => '1996-09-01',
        'end_date' => '2001-06-30',
        'fiz_code' => 'J5uL0pSxDa',
      ],
    ];

    return $result;
  }

  /**
   * Возвращает список медицинских посещений (статический массив) за текущий месяц.
   *
   * Каждая запись содержит:
   * - visit_date: дата посещения (YYYY-MM-DD), август 2025
   * - amount: сумма визита в рублях (100..500)
   * - fiz_code: код сотрудника
   *
   * На каждого сотрудника 0–6 посещений; внутри одного сотрудника даты уникальны.
   *
   * @return array<int, array{
   *   visit_date: string,
   *   amount: int,
   *   fiz_code: string,
   * }>
   */
  public function getMedicine(): array {
    $result = [
      [ 'visit_date' => '2025-08-02', 'amount' => 350, 'fiz_code' => 'AdVUAlmVdy' ],
      [ 'visit_date' => '2025-08-15', 'amount' => 420, 'fiz_code' => 'AdVUAlmVdy' ],
      [ 'visit_date' => '2025-08-28', 'amount' => 180, 'fiz_code' => 'AdVUAlmVdy' ],
      [ 'visit_date' => '2025-08-05', 'amount' => 500, 'fiz_code' => 'oA80a12fi9' ],
      [ 'visit_date' => '2025-08-20', 'amount' => 260, 'fiz_code' => 'oA80a12fi9' ],
      [ 'visit_date' => '2025-08-11', 'amount' => 145, 'fiz_code' => '3vaj94Dh1E' ],
      [ 'visit_date' => '2025-08-01', 'amount' => 300, 'fiz_code' => '0frZxKU5kQ' ],
      [ 'visit_date' => '2025-08-09', 'amount' => 110, 'fiz_code' => '0frZxKU5kQ' ],
      [ 'visit_date' => '2025-08-19', 'amount' => 275, 'fiz_code' => '0frZxKU5kQ' ],
      [ 'visit_date' => '2025-08-29', 'amount' => 490, 'fiz_code' => '0frZxKU5kQ' ],
      [ 'visit_date' => '2025-08-07', 'amount' => 365, 'fiz_code' => 'pa4ixLPZoB' ],
      [ 'visit_date' => '2025-08-27', 'amount' => 210, 'fiz_code' => 'pa4ixLPZoB' ],
      [ 'visit_date' => '2025-08-03', 'amount' => 220, 'fiz_code' => 'P0Z1JyjcBC' ],
      [ 'visit_date' => '2025-08-06', 'amount' => 330, 'fiz_code' => 'P0Z1JyjcBC' ],
      [ 'visit_date' => '2025-08-12', 'amount' => 410, 'fiz_code' => 'P0Z1JyjcBC' ],
      [ 'visit_date' => '2025-08-18', 'amount' => 125, 'fiz_code' => 'P0Z1JyjcBC' ],
      [ 'visit_date' => '2025-08-24', 'amount' => 500, 'fiz_code' => 'P0Z1JyjcBC' ],
      [ 'visit_date' => '2025-08-30', 'amount' => 199, 'fiz_code' => 'P0Z1JyjcBC' ],
      [ 'visit_date' => '2025-08-08', 'amount' => 340, 'fiz_code' => 'TuFcUnUu5i' ],
      [ 'visit_date' => '2025-08-04', 'amount' => 150, 'fiz_code' => 'vl7WaqD1D3' ],
      [ 'visit_date' => '2025-08-10', 'amount' => 480, 'fiz_code' => 'vl7WaqD1D3' ],
      [ 'visit_date' => '2025-08-14', 'amount' => 205, 'fiz_code' => 'vl7WaqD1D3' ],
      [ 'visit_date' => '2025-08-21', 'amount' => 399, 'fiz_code' => 'vl7WaqD1D3' ],
      [ 'visit_date' => '2025-08-31', 'amount' => 260, 'fiz_code' => 'vl7WaqD1D3' ],
      [ 'visit_date' => '2025-08-16', 'amount' => 320, 'fiz_code' => 'FITPlEpy3a' ],
      [ 'visit_date' => '2025-08-26', 'amount' => 175, 'fiz_code' => 'FITPlEpy3a' ],
      [ 'visit_date' => '2025-08-02', 'amount' => 405, 'fiz_code' => 'PVgFuCWFzA' ],
      [ 'visit_date' => '2025-08-22', 'amount' => 135, 'fiz_code' => 'PVgFuCWFzA' ],
      [ 'visit_date' => '2025-08-25', 'amount' => 500, 'fiz_code' => 'PVgFuCWFzA' ],
      [ 'visit_date' => '2025-08-13', 'amount' => 190, 'fiz_code' => 'gnzfrmZTop' ],
      [ 'visit_date' => '2025-08-01', 'amount' => 500, 'fiz_code' => 'eB3kL9aQ2M' ],
      [ 'visit_date' => '2025-08-05', 'amount' => 220, 'fiz_code' => 'eB3kL9aQ2M' ],
      [ 'visit_date' => '2025-08-15', 'amount' => 145, 'fiz_code' => 'eB3kL9aQ2M' ],
      [ 'visit_date' => '2025-08-17', 'amount' => 300, 'fiz_code' => 'eB3kL9aQ2M' ],
      [ 'visit_date' => '2025-08-23', 'amount' => 265, 'fiz_code' => 'eB3kL9aQ2M' ],
      [ 'visit_date' => '2025-08-28', 'amount' => 180, 'fiz_code' => 'eB3kL9aQ2M' ],
      [ 'visit_date' => '2025-08-09', 'amount' => 350, 'fiz_code' => 'Hn7yP0sXcV' ],
      [ 'visit_date' => '2025-08-20', 'amount' => 410, 'fiz_code' => 'Hn7yP0sXcV' ],
      [ 'visit_date' => '2025-08-03', 'amount' => 125, 'fiz_code' => 'Qw2Er9TtY1' ],
      [ 'visit_date' => '2025-08-07', 'amount' => 275, 'fiz_code' => 'Qw2Er9TtY1' ],
      [ 'visit_date' => '2025-08-11', 'amount' => 360, 'fiz_code' => 'Qw2Er9TtY1' ],
      [ 'visit_date' => '2025-08-29', 'amount' => 200, 'fiz_code' => 'Qw2Er9TtY1' ],
      [ 'visit_date' => '2025-08-04', 'amount' => 240, 'fiz_code' => 'mN8bVc4KzR' ],
    ];

    return $result;
  }

  /**
   * Возвращает список нарушений (статический массив) за 2024–2025 годы.
   *
   * Каждая запись содержит:
   * - violation_date: дата нарушения (YYYY-MM-DD), в пределах 2024–2025
   * - violation_type: тип нарушения ("замечание" или "выговор")
   * - violation_code: числовой код типа (1 для замечания, 2 для выговора)
   * - fiz_code: код сотрудника
   *
   * На каждого сотрудника 0–3 нарушения; внутри одного сотрудника даты уникальны.
   *
   * @return array<int, array{
   *   violation_date: string,
   *   violation_type: string,
   *   violation_code: int,
   *   fiz_code: string,
   * }>
   */
  public function getViolation(): array {
    $result = [
      [ 'violation_date' => '2025-03-12', 'violation_type' => 'замечание', 'violation_code' => 1, 'fiz_code' => 'AdVUAlmVdy' ],
      [ 'violation_date' => '2024-11-05', 'violation_type' => 'выговор',   'violation_code' => 2, 'fiz_code' => 'AdVUAlmVdy' ],
      [ 'violation_date' => '2025-07-01', 'violation_type' => 'замечание', 'violation_code' => 1, 'fiz_code' => 'oA80a12fi9' ],
      [ 'violation_date' => '2024-02-20', 'violation_type' => 'выговор',   'violation_code' => 2, 'fiz_code' => '3vaj94Dh1E' ],
      [ 'violation_date' => '2024-09-14', 'violation_type' => 'замечание', 'violation_code' => 1, 'fiz_code' => '3vaj94Dh1E' ],
      [ 'violation_date' => '2025-05-03', 'violation_type' => 'замечание', 'violation_code' => 1, 'fiz_code' => '3vaj94Dh1E' ],
      [ 'violation_date' => '2024-06-18', 'violation_type' => 'выговор',   'violation_code' => 2, 'fiz_code' => '0frZxKU5kQ' ],
      [ 'violation_date' => '2025-02-08', 'violation_type' => 'замечание', 'violation_code' => 1, 'fiz_code' => 'pa4ixLPZoB' ],
      [ 'violation_date' => '2024-10-27', 'violation_type' => 'выговор',   'violation_code' => 2, 'fiz_code' => 'pa4ixLPZoB' ],
      [ 'violation_date' => '2025-01-19', 'violation_type' => 'замечание', 'violation_code' => 1, 'fiz_code' => 'TuFcUnUu5i' ],
      [ 'violation_date' => '2024-03-07', 'violation_type' => 'замечание', 'violation_code' => 1, 'fiz_code' => 'vl7WaqD1D3' ],
      [ 'violation_date' => '2025-04-22', 'violation_type' => 'выговор',   'violation_code' => 2, 'fiz_code' => 'vl7WaqD1D3' ],
      [ 'violation_date' => '2024-12-02', 'violation_type' => 'выговор',   'violation_code' => 2, 'fiz_code' => 'zajhQxB6gV' ],
      [ 'violation_date' => '2024-01-11', 'violation_type' => 'замечание', 'violation_code' => 1, 'fiz_code' => 'PVgFuCWFzA' ],
      [ 'violation_date' => '2024-08-08', 'violation_type' => 'замечание', 'violation_code' => 1, 'fiz_code' => 'PVgFuCWFzA' ],
      [ 'violation_date' => '2025-06-16', 'violation_type' => 'выговор',   'violation_code' => 2, 'fiz_code' => 'PVgFuCWFzA' ],
      [ 'violation_date' => '2025-03-29', 'violation_type' => 'выговор',   'violation_code' => 2, 'fiz_code' => 'sTA8a9BrfG' ],
      [ 'violation_date' => '2024-05-25', 'violation_type' => 'замечание', 'violation_code' => 1, 'fiz_code' => 'eB3kL9aQ2M' ],
      [ 'violation_date' => '2025-07-07', 'violation_type' => 'выговор',   'violation_code' => 2, 'fiz_code' => 'eB3kL9aQ2M' ],
      [ 'violation_date' => '2024-04-14', 'violation_type' => 'замечание', 'violation_code' => 1, 'fiz_code' => 'Qw2Er9TtY1' ],
      [ 'violation_date' => '2025-02-01', 'violation_type' => 'выговор',   'violation_code' => 2, 'fiz_code' => 'Qw2Er9TtY1' ],
      [ 'violation_date' => '2024-07-19', 'violation_type' => 'замечание', 'violation_code' => 1, 'fiz_code' => 'mN8bVc4KzR' ],
    ];

    return $result;
  }
}
