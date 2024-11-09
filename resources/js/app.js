import "./bootstrap";
import "../scss/app.scss";
import "../scss/themes/dark/app-dark.scss";

import { Livewire } from "../../vendor/livewire/livewire/dist/livewire.esm";
Livewire.start();

import.meta.glob(["../images/**", "../fonts/**"]);
