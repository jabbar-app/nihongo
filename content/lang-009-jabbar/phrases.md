## LANG-009 Jabbar Interview — Phrasebank (Keigo / Self-PR / STAR)

Formal interview Japanese with customizable slots and ICU/engineering context.

---

### Self-Introduction (自己紹介)
| JP | Romaji | EN | Notes |
|---|---|---|---|
| はじめまして。ジャッバーと申します。 | Hajimemashite. Jabbar to mōshimasu. | Nice to meet you. I’m Jabbar. | Replace name if needed |
| 本日はお時間をいただき、誠にありがとうございます。 | Honjitsu wa o-jikan o itadaki, makoto ni arigatō gozaimasu. | Thank you for your time today. | Opening courtesy |
| 現在はフルスタックエンジニアとして、医療領域の開発に従事しております。 | Genzai wa furusutakku enjiniā to shite, iryō ryōiki no kaihatsu ni jūji shite orimasu. | I work as a full‑stack engineer in healthcare. | Context |
| 主にReact/TypeScriptとPython/Flask、Docker、PostgreSQLを扱っています。 | Omo ni React/TypeScript to Python/Flask, Dokkā, Posutoguresu o atsukatte imasu. | Mainly React/TS, Python/Flask, Docker, PostgreSQL. | Tech stack |
| 本日は、経歴の概要と志望動機、強みをご説明いたします。 | Honjitsu wa, keireki no gaiyō to shibō dōki, tsuyomi o go-setsumei itashimasu. | Today I’ll explain my background, motivation, and strengths. | Agenda |

### Motivation (志望動機)
| JP | Romaji | EN | Notes |
|---|---|---|---|
| 貴社のミッションと医療DXへの貢献に強く共感しております。 | Kisha no misshon to iryō DX e no kōken ni tsuyoku kyōkan shite orimasu. | I strongly resonate with your mission and healthcare DX. | |
| 現場運用に耐える品質基準とユーザ中心設計に魅力を感じました。 | Genba un’yō ni taeru hinshitsu kijun to yūza chūshin sekkei ni miryoku o kanjimashita. | I value your quality bar and user‑centered design. | |
| 日本語での要件定義と医療分野のコミュニケーション力を伸ばしたいです。 | Nihongo de no yōken teigi to iryō bun’ya no komyunikēshon‑ryoku o nobashitai desu. | I want to grow requirements gathering in Japanese. | |

### Strengths (強み)
| JP | Romaji | EN | Notes |
|---|---|---|---|
| 課題の言語化と合意形成が得意です。 | Kadai no gengoka to gōi keisei ga tokui desu. | Good at problem framing and alignment. | |
| 再現性のある手順書・Runbook作成を徹底しています。 | Saigensei no aru tejunsho / Runbook sakusei o tettei shite imasu. | I create reproducible runbooks. | |
| セキュリティと可観測性（ログ／メトリクス）を意識した設計を行います。 | Sekyuriti to kakansokusei (rogu/metorikusu) o ishiki shita sekkei o okonaimasu. | Design with security and observability in mind. | |

### Project Explanation (プロジェクト説明)
| JP | Romaji | EN | Notes |
|---|---|---|---|
| ICU向けのデータ連携基盤で、FHIR R4に準拠したAPIを提供しました。 | ICU‑muke no dēta renkei kiban de, FHIR R4 ni junkyo shita API o teikyō shimashita. | Built ICU data platform with FHIR R4 APIs. | |
| バイタルと人工呼吸器設定、入出量の整合性チェックを実装しました。 | Baitaru to jinkō kokyūki settei, nyūshutsuryō no seigōsei chekku o jissō shimashita. | Implemented vitals, ventilator, I/O consistency checks. | |
| 認証はJWT、監査用にセッションを記録し、例外はOperationOutcomeで返却しました。 | Ninshō wa JWT, kansa‑yō ni sesshon o kiroku shi, reigai wa OperationOutcome de henkyaku shimashita. | JWT auth, session logging, FHIR error responses. | |

### STAR Framework (行動事例)
| JP | Romaji | EN | Notes |
|---|---|---|---|
| 状況：UATで境界値のIN/OUT表示が不整合でした。 | Jōkyō: UAT de kyōkaichi no IN/OUT hyōji ga fuseigō deshita. | Situation: I/O edge case inconsistency. | |
| 課題：分子・分母の`undefined`が混在していました。 | Kadai: Bunshi/bunbo no undefined ga konzai shite imashita. | Task: Undefined numerator/denominator mix. | |
| 行動：ヘルパー関数を再設計し、単体・結合テストを追加しました。 | Kōdō: Herupā kansū o saisekkei shi, tantai/ketsugō tesuto o tsuika shimashita. | Action: Redesigned helper; added tests. | |
| 結果：負の値の誤表示が解消し、UATを通過しました。 | Kekka: Fu no atai no gohyōji ga kaishō shi, UAT o tsūka shimashita. | Result: Fixed; passed UAT. | |

### Clarifying / Confirming (確認・すり合わせ)
| JP | Romaji | EN | Notes |
|---|---|---|---|
| 前提条件の確認をさせていただいてもよろしいでしょうか。 | Zentei jōken no kakunin o sasete itadaite mo yoroshii deshō ka. | May I confirm assumptions? | |
| 受け入れ条件は具体的にどのように定義されていますか。 | Ukeire jōken wa gutaiteki ni dono yō ni teigi sarete imasu ka. | How are acceptance criteria defined? | |
| エッジケースの範囲を共有いただけますか。 | Ejji kēsu no han’i o kyōyū itadakemasu ka. | Could you share edge case scope? | |

### Reverse Questions (逆質問)
| JP | Romaji | EN | Notes |
|---|---|---|---|
| チーム構成と日本語でのコミュニケーションの頻度を教えてください。 | Chīmu kōsei to Nihongo de no komyunikēshon no hindo o oshiete kudasai. | Team structure and JP comms frequency? | |
| 入社後3か月の期待成果を伺ってもよろしいでしょうか。 | Nyūsha‑go sankagetsu no kitai seika o ukagatte mo yoroshii deshō ka. | Expectations for first 3 months? | |
| 運用・保守で重要視されている指標は何でしょうか。 | Un’yō/hoshu de jūyōshi sarete iru shihyō wa nan deshō ka. | Key ops/maintenance metrics? | |

### Closing (締め)
| JP | Romaji | EN | Notes |
|---|---|---|---|
| 本日は貴重なお話をありがとうございました。 | Honjitsu wa kichō na o‑hanashi o arigatō gozaimashita. | Thank you for the valuable discussion. | |
| 本件につき、追加情報が必要でしたらお知らせください。 | Honken ni tsuki, tsuika jōhō ga hitsuyō deshitara oshirase kudasai. | Please let me know if you need more info. | |
| ご連絡をお待ち申し上げております。 | Go‑renraku o omachi mōshiageて orimasu. | I look forward to hearing from you. | Very polite |



