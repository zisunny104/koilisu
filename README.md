# KoiLiSu 開利手

> 讓生活中的小事更順手的開放專案

## 介紹

KoiLiSu（開利手）是一個開放的小工具集合，將一些讓日常使用更加順手的工具集中於此。

## 使用

KoiLiSu 提供各項工具的網頁入口，可直接使用：

https://toka.dev/koilisu/

## 維護

本站由作者維運，各工具以獨立 repository 與 Git submodule 維護。更新時使用主專案已審閱的子專案版本；原有更新工具保留供維運使用。

## 專案結構

```text
koilisu/
├── common/           # 共用功能
│   ├── functions.php
│   ├── header.php
│   └── footer.php
├── pages/            # 靜態頁面
│   ├── home.php
│   └── docs.php
├── templates/        # 工具範本
│   └── app-template/
├── apps/             # 各工具子專案（gradcheck、kobeu、pitrace、hapbun、printan）
├── .gitmodules       # submodule 設定
└── index.php         # 主入口
```

各工具以獨立 repository 維護，並透過 Git submodule 與 KoiLiSu 串接。

## 專案命名

「開利手」取名自「開放」與「順手」的概念，希望工具能夠開放使用，也讓日常使用更加順手。

名稱中的「開」、「利」、「手」三字，取自客語四縣腔的讀音，並以其讀音組成 **KoiLiSu**。

四縣腔客語拼音：

**koiˊ · liˊ · suˋ**

## 回報問題

如果使用上遇到問題，可以提出 Issue。

## 授權

本專案採用 MIT License，詳見 [LICENSE](LICENSE)。

`apps/` 下的各子專案為獨立 repository，授權方式依各專案的 LICENSE 為準。

## 作者

Tokas (Xiang-zi Xie)
