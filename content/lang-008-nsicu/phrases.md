## LANG-008 NSICU Work — Phrasebank (Meetings / Dev / Clinical Context)

Polite workplace Japanese with practical engineering and hospital terms.

---

### Standups & Status
| JP | Romaji | EN | Notes |
|---|---|---|---|
| 昨日の作業は〇〇です。今日は△△を進めます。 | Kinō no sagyō wa 〇〇 desu. Kyō wa △△ o susumemasu. | Yesterday I did ~. Today I’ll work on ~. | Daily standup |
| ブロッカーはありません。 | Burokkā wa arimasen. | No blockers. | |
| 一点、レビューをお願いできますか。 | Itten, rebyū o onegai dekimasu ka? | Could you review one item? | |
| 終了条件は定義済みです。 | Shūryō jōken wa teigi-zumi desu. | Acceptance criteria are defined. | |

### Tickets & Releases
| JP | Romaji | EN | Notes |
|---|---|---|---|
| チケット〇〇は完了し、PRを出しました。 | Chiketto 〇〇 wa kanryō shi, PīĀru o dashimashita. | Ticket ~ is done; PR opened. | Replace 〇〇 |
| マージ後、ステージングにデプロイします。 | Māji-go, sutējingu ni depuroi shimasu. | After merge, I’ll deploy to staging. | |
| UATのフィードバックを反映しました。 | YūĒTī no fīdobakku o han’ei shimashita. | Incorporated UAT feedback. | |
| リリースノートを更新しました。 | Rirīsu nōto o kōshin shimashita. | Updated the release notes. | |

### Backend (API / Auth / FHIR)
| JP | Romaji | EN | Notes |
|---|---|---|---|
| 認証はJWTで実装しています。 | Ninshō wa JWT de jissō shite imasu. | Auth is implemented with JWT. | |
| セッションは`user_session`テーブルに記録します。 | Sesshon wa `user_session` tēburu ni kiroku shimasu. | Sessions are recorded in the `user_session` table. | |
| FHIRの`Observation`を返します。 | Faiā no `Observation` o kaeshimasu. | Returns a FHIR Observation. | |
| `/fhir/metadata`で稼働確認できます。 | /fhir/metadata de kadō kakunin dekimasu. | Health/capability check at `/fhir/metadata`. | |
| 例外は`OperationOutcome`で返します。 | Reigai wa OperationOutcome de kaeshimasu. | Errors return OperationOutcome. | |

### Clinical Data (Vitals / Vent / I/O)
| JP | Romaji | EN | Notes |
|---|---|---|---|
| バイタルはOCR結果から保存します。 | Baitaru wa OCR kekka kara hozon shimasu. | Vitals saved from OCR output. | |
| 収縮期と拡張期のペアを検証します。 | Shūshukki to kakuchōki no pea o kenshō shimasu. | Validate systolic/diastolic pairing. | |
| 人工呼吸器設定を登録しました。 | Jinkō kokyūki settei o tōroku shimashita. | Saved ventilator settings. | |
| IN/OUTバランスを計算して表示します。 | In/Out baransu o keisan shite hyōji shimasu. | Calculate and display I/O balance. | |
| 単位と時間帯を確認してください。 | Tan’i to jikantai o kakunin shite kudasai. | Please validate units and time window. | |

### Frontend (UI / State / Services)
| JP | Romaji | EN | Notes |
|---|---|---|---|
| サービス経由でAPIを呼び出します。 | Sābisu keiyu de API o yobidashimasu. | Call APIs via the service layer. | |
| 型は`types`に定義しています。 | Kata wa `types` ni teigi shite imasu. | Types are defined in `types`. | |
| バランス表はユーティリティで計算します。 | Baransu hyō wa yūtiritī de keisan shimasu. | Balance table calculated in utilities. | |
| 入力のバリデーションを追加しました。 | Nyūryoku no baridēshon o tsuika shimashita. | Added input validation. | |

### Meetings & Handoffs
| JP | Romaji | EN | Notes |
|---|---|---|---|
| この変更は後方互換です。 | Kono henkō wa kōhō gokan desu. | This change is backward-compatible. | |
| 影響範囲は限定的です。 | Eikyō han’i wa gentei-teki desu. | Impact is limited. | |
| ロールバック手順はここです。 | Rōrubakku tejun wa koko desu. | Rollback steps are here. | |
| 検証観点を共有します。 | Kenshō kanten o kyōyū shimasu. | I’ll share the test perspectives. | |
| 本番適用は業務時間外で行います。 | Honban tekiyō wa gyōmu jikan-gai de okonaimasu. | Production rollout after hours. | |

### Requests & Clarifications
| JP | Romaji | EN | Notes |
|---|---|---|---|
| 要件をもう一度確認してもいいですか。 | Yōken o mō ichido kakunin shite mo ii desu ka? | May I reconfirm the requirements? | |
| 仕様の前提を教えてください。 | Shiyō no zentei o oshiete kudasai. | Please share the assumptions. | |
| エッジケースの例がありますか。 | Ejji kēsu no rei ga arimasu ka? | Any edge case examples? | |
| スクリーンショットをいただけますか。 | Sukurīnshotto o itadakemasu ka? | Could you share a screenshot? | |

### Closings
| JP | Romaji | EN | Notes |
|---|---|---|---|
| ご確認ありがとうございます。 | Gokakunin arigatō gozaimasu. | Thanks for checking. | |
| 次回の打ち合わせで報告します。 | Jikai no uchiawase de hōkoku shimasu. | I’ll report in the next meeting. | |
| 本件はクローズします。 | Honken wa kurōzu shimasu. | I’ll close this item. | |



