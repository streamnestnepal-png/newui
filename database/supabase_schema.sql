create table if not exists public.users (
  id bigint primary key,
  name text not null,
  email text not null unique,
  image text,
  password_hash text,
  ip_address text,
  is_active boolean not null default false,
  date_created bigint not null,
  inacash numeric not null default 0,
  synced_at timestamptz not null default now()
);

alter table public.users add column if not exists password_hash text;
alter table public.users add column if not exists ip_address text;

create table if not exists public.checkout_sessions (
  id bigint primary key,
  checkout_id text not null unique,
  email text not null,
  customer_name text not null,
  phone text not null,
  user_id text,
  server_id text,
  product text not null,
  package text not null,
  amount integer not null,
  status text not null,
  payment_id text,
  created_at bigint not null,
  synced_at timestamptz not null default now()
);

create table if not exists public.orders (
  id bigint primary key,
  order_id text not null unique,
  checkout_id text not null unique,
  payment_id text not null unique,
  email text not null,
  customer_name text not null,
  phone text not null,
  user_id text,
  server_id text,
  product text not null,
  package text not null,
  amount integer not null,
  status text not null,
  confirmation_sent_at bigint not null default 0,
  credentials_sent_at bigint not null default 0,
  created_at bigint not null,
  synced_at timestamptz not null default now()
);

create table if not exists public.telegram_delivery_drafts (
  id bigint primary key,
  order_id text not null,
  chat_id text not null,
  message_id text not null default '',
  draft_message text not null,
  status text not null default 'waiting_input',
  created_at bigint not null,
  synced_at timestamptz not null default now()
);
