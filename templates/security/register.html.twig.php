{% extends 'base.html.twig' %}

{% block title %}rejestracja{% endblock %}

{% block body %}
<div style="max-width: 420px; margin: 4rem auto;">
    <div class="app-card">
        <h1 style="text-align: center; margin-bottom: 0.5rem;">
            <i class="bi bi-wallet2" style="color: var(--accent-yellow);"></i>
        </h1>
        <h4 style="text-align: center; margin-bottom: 1.5rem;">utwórz konto</h4>

        {{ form_start(form) }}
        <div class="mb-3">
            {{ form_label(form.email) }}
            {{ form_widget(form.email) }}
            {{ form_errors(form.email) }}
        </div>
        <div class="mb-3">
            {{ form_label(form.plainPassword.first) }}
            {{ form_widget(form.plainPassword.first) }}
            {{ form_errors(form.plainPassword.first) }}
        </div>
        <div class="mb-3">
            {{ form_label(form.plainPassword.second) }}
            {{ form_widget(form.plainPassword.second) }}
            {{ form_errors(form.plainPassword.second) }}
        </div>
        <button type="submit" class="btn-accent" style="width: 100%;">zarejestruj się</button>
        {{ form_end(form) }}

        <p style="text-align: center; margin-top: 1rem; font-size: 13px; color: var(--text-muted);">
            masz już konto? <a href="{{ path('app_login') }}">zaloguj się</a>
        </p>
    </div>
</div>
{% endblock %}
