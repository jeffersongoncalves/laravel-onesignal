# Changelog

All notable changes to this project will be documented in this file.

## v1.0.0 - 2026-09-07

First release. Laravel wrapper for the OneSignal REST API. Notifications (send, list, get, cancel), segments (list, create, delete), users (get, create, delete), templates (list, get, create) and app (get), exposed through the OneSignal facade and backed by Laravel's Http client. Non-2xx responses throw OneSignalException carrying the decoded API error body.

## [Unreleased]
