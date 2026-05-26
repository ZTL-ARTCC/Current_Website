<!doctype html>
<html lang="ENG">
    <head>
        {{-- Meta Stuff --}}
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="vZTL ARTCC Website. For entertainment purposes only. Do not use for real world purposes. Part of the VATSIM Network.">
        <meta name="keywords" content="ztl,vatusa,vatsim,atlanta,center,georgia,artcc,aviation,airplane,airport,charlotte,controller,atc,air,traffic,control,pilot">
        <meta name="author" content="ZTL Web Team">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        {{-- Dark mode toggle. Must be here and not elsewhere to ensure it runs before the page loads --}}
        {{-- and before all other scripts, to prevent flickering. --}}
        <script>
            /*!
 * Color mode toggler for Bootstrap's docs (https://getbootstrap.com/)
 * Copyright 2011-2025 The Bootstrap Authors
 * Licensed under the Creative Commons Attribution 3.0 Unported License.
 */

            (() => {
                'use strict'

                const getStoredTheme = () => localStorage.getItem('theme')
                const setStoredTheme = theme => localStorage.setItem('theme', theme)

                const getPreferredTheme = () => {
                    const storedTheme = getStoredTheme()
                    if (storedTheme) {
                        return storedTheme
                    }

                    return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'
                }

                const setTheme = theme => {
                    if (theme === 'auto') {
                        document.documentElement.setAttribute('data-bs-theme', (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'))
                    } else {
                        document.documentElement.setAttribute('data-bs-theme', theme)
                    }
                }

                setTheme(getPreferredTheme())

                const showActiveTheme = (theme, focus = false) => {
                    const btnToActive = document.querySelector(`[data-bs-theme-value="${theme}"]`)

                    document.querySelectorAll('[data-bs-theme-value]').forEach(element => {
                        element.classList.remove('active')
                        element.setAttribute('aria-pressed', 'false')
                    })

                    btnToActive.classList.add('active')
                    btnToActive.setAttribute('aria-pressed', 'true')
                }

                window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
                    const storedTheme = getStoredTheme()
                    if (storedTheme !== 'light' && storedTheme !== 'dark') {
                        setTheme(getPreferredTheme())
                    }
                })

                window.addEventListener('DOMContentLoaded', () => {
                    showActiveTheme(getPreferredTheme())

                    document.querySelectorAll('[data-bs-theme-value]')
                        .forEach(toggle => {
                            toggle.addEventListener('click', () => {
                                const theme = toggle.getAttribute('data-bs-theme-value')
                                setStoredTheme(theme)
                                setTheme(theme)
                                showActiveTheme(theme, true)
                            })
                        })
                })
            })()
        </script>

        {{-- Stylesheets --}}
        @vite('resources/assets/sass/app.scss')
        @vite('resources/assets/sass/dashboard.scss')
        @vite('resources/assets/sass/main.scss')
        @vite('resources/assets/sass/footer_white.scss')

        {{-- Custom JS --}}
        @vite('resources/assets/js/app.js')

        {{-- Custom Headers --}}
        @stack('custom_header')

        {{-- Sidebar Menu Styles --}}
        @vite('resources/assets/sass/sidebar.scss')

        {{-- Title --}}
        <title>
            @yield('title') | ZTL ARTCC
        </title>
    </head>
    <body>
        {{-- Impersonation Warning --}}
        <div class="container">
            @include('inc.impersonation_warning')
        </div>

        {{-- Messages --}}
        <div class="container">
            @include('inc.messages')
        </div>

        {{-- Navbar --}}
        @include('inc.dashboard_head')

        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-2">
                    {{-- Sidebar --}}
                    @include('inc.sidebar')
                </div>
                <div class="col-sm-10">
                    {{-- Content --}}
                    @yield('content')
                </div>
            </div>
        </div>

        {{-- Footer --}}
        @include('inc.footer')
    </body>
</html>
