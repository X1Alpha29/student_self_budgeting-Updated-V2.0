<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Web Application Version 2.0 Updates and Fixes Checklist

## Dashboard
- [x] Added a line chart for Income Over Time.
- [x] Made widget-cards clickable for navigation to the corresponding page.

## Budgets
- [x] Changed the button text to "+ Budget".
- [x] Added an alert message to the update page for user feedback.
- [x] Updated style and size of the update page for better UX.

## Expenses
- [x] Changed the button text to "+ Expense".
- [x] Made category selection required to prevent page refresh without selection.
- [x] Updated the Update page style and size for consistency.

## Debts
- [x] Fixed the view payments feature for logged debts.
- [x] Changed the button text to "+ Debt".
- [x] Updated the log debt card color to dark for better visibility.
- [x] Changed "Days remaining" to "Late payment" when the time runs out.

## Direct Debit
- [x] Fixed the Update button functionality.
- [x] Made details field required to prevent page break on empty submission.

## Finance
- [x] Changed card size for better layout and readability.
- [x] Added loan type options: Maintenance, Tuition Fee, Other.
- [x] Added status options: Approved, Cancelled, In Progress, Other.
- [x] Fixed the Update button routing problem.
- [x] Changed the form save button color to green for clarity and consistency.

## Income
- [x] Added an Income page for logging and managing income records.
