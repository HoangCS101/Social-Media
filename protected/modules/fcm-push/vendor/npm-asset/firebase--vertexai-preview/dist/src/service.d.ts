/**
 * @license
 * Copyright 2024 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
import { FirebaseApp, _FirebaseService } from '@firebase/app';
import { VertexAI, VertexAIOptions } from './public-types';
import { AppCheckInternalComponentName, FirebaseAppCheckInternal } from '@firebase/app-check-interop-types';
import { Provider } from '@firebase/component';
import { FirebaseAuthInternal, FirebaseAuthInternalName } from '@firebase/auth-interop-types';
export declare class VertexAIService implements VertexAI, _FirebaseService {
    app: FirebaseApp;
    options?: VertexAIOptions | undefined;
    auth: FirebaseAuthInternal | null;
    appCheck: FirebaseAppCheckInternal | null;
    location: string;
    constructor(app: FirebaseApp, authProvider?: Provider<FirebaseAuthInternalName>, appCheckProvider?: Provider<AppCheckInternalComponentName>, options?: VertexAIOptions | undefined);
    _delete(): Promise<void>;
}
