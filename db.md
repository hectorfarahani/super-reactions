# DATABASE MODEL
## Super Reactions

| Column       | Type |
| --------     | ---- |
| `id`         | `int(11) Auto Increment` |
| `type`       | `tinytext` |
| `section`    | `tinytext` |
| `value`      | `tinytext` |
| `time`       | `datetime [0000-00-00 00:00:00]` |
| `user_id`    | `int(11) [0]` |
| `content_id` | `int(11) [0]` |
| `ip`         | `tinytext` |
